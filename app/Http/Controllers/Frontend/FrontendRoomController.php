<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Room;
use App\Models\RoomPackage;
use App\Models\RoomType;
use App\Models\Hotel;
use App\Models\PromoCode;
use App\Models\RoomBookedDate;
use App\Models\BookingRoomList;
use App\Models\RoomNumber;
use Illuminate\Support\Facades\Log;

class FrontendRoomController extends Controller
{
    public function CheckAvailability(Request $request) 
    {
        $request->flash();

        // Validate input
        if ($request->check_in == $request->check_out) {
            $notification = [
                'message' => 'Check-in and check-out dates cannot be the same',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }

        $total_guests = (int)$request->adults + (int)$request->children;
        $hotels = Hotel::all();
        
        // Create array of dates to check - IMPORTANT: Use both formats
        $check_in = Carbon::parse($request->check_in);
        $check_out = Carbon::parse($request->check_out);
        
        $dateStringsDisplay = []; // For display (d-m-Y)
        $dateStringsDB = []; // For database queries (Y-m-d)
        
        $dateRange = CarbonPeriod::create($check_in, $check_out->copy()->subDay());
        foreach ($dateRange as $date) {
            $dateStringsDisplay[] = $date->format('d-m-Y');
            $dateStringsDB[] = $date->format('Y-m-d');
        }
        
        // Log dates for debugging
        Log::debug('Check Availability - Dates to check:', [
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'display_dates' => $dateStringsDisplay,
            'db_dates' => $dateStringsDB
        ]);
        
        // Important: Get all booked room TYPES even if room_number_id is null
        $bookedDatesByRoomType = RoomBookedDate::whereIn('book_date', $dateStringsDB)
            ->select('room_id')
            ->distinct()
            ->get()
            ->pluck('room_id')
            ->toArray();
        
        Log::debug('Rooms with bookings for these dates:', [
            'room_ids' => $bookedDatesByRoomType
        ]);
        
        // Get room types with their related data
        $roomTypesQuery = RoomType::with([
            'room.facilities', 
            'room.multiImages', 
            'hotel', 
            'room.room_numbers',
            'room.promoCodes'
        ]);

        // Filter by hotel if specified
        if ($request->hotel_slug && $request->hotel_slug != 'Select Hotel') {
            $roomTypesQuery->whereHas('hotel', function($query) use ($request) {
                $query->where('slug', $request->hotel_slug);
            });
        }

        // Filter by basic room capacity and status
        $roomTypesQuery->whereHas('room', function($query) use ($total_guests) {
            $query->where('guests_total', '>=', $total_guests)
                  ->where('status', 1);
        });

        // Get all potential room types
        $roomTypes = $roomTypesQuery->get();
        
        Log::debug('Initial room types count:', ['count' => $roomTypes->count()]);
        
        // Get all bookings for the requested dates from room_booked_dates table
        $bookedDatesInfo = RoomBookedDate::whereIn('book_date', $dateStringsDB)
            ->get(['booking_id', 'room_id', 'room_number_id', 'book_date']);
            
        Log::debug('Booked dates from room_booked_dates:', [
            'count' => $bookedDatesInfo->count(),
            'dates' => $bookedDatesInfo->pluck('book_date')->unique()->toArray(),
            'room_ids' => $bookedDatesInfo->pluck('room_id')->unique()->toArray(),
            'room_number_ids' => $bookedDatesInfo->pluck('room_number_id')->unique()->toArray()
        ]);
        
        // Create a lookup of all bookings by room_id
        $bookingsByRoomId = $bookedDatesInfo->groupBy('room_id');
        
        // Filter by available room numbers
        $availableRoomTypes = $roomTypes->filter(function($roomType) use ($dateStringsDB, $total_guests, $bookedDatesByRoomType, $bookingsByRoomId) {
            if (!$roomType->room) {
                return false;
            }
            
            $roomId = $roomType->room->id;
            
            // If the room type is in the booked list for these dates, check more carefully
            if (in_array($roomId, $bookedDatesByRoomType)) {
                // Get all active room numbers for this room
                $activeRoomNumbers = $roomType->room->room_numbers->where('status', 'Active');
                
                if ($activeRoomNumbers->isEmpty()) {
                    return false;
                }
                
                // Get all bookings for this room ID
                $roomBookings = $bookingsByRoomId[$roomId] ?? collect([]);
                
                // Get unique booked room numbers (excluding nulls)
                $bookedRoomNumberIds = $roomBookings->pluck('room_number_id')
                    ->filter() // Remove null values
                    ->unique()
                    ->toArray();
                
                // Also count bookings with null room_number_id (important!)
                $nullRoomNumberBookings = $roomBookings->whereNull('room_number_id')
                    ->pluck('booking_id')
                    ->unique()
                    ->count();
                
                // Calculate available rooms count
                $totalActiveRooms = $activeRoomNumbers->count();
                $bookedRoomsCount = count($bookedRoomNumberIds) + $nullRoomNumberBookings;
                $availableRoomsCount = $totalActiveRooms - $bookedRoomsCount;
                
                Log::debug('Room availability check:', [
                    'room_type' => $roomType->name,
                    'active_rooms' => $totalActiveRooms,
                    'booked_room_ids' => $bookedRoomNumberIds,
                    'null_room_bookings' => $nullRoomNumberBookings,
                    'available_rooms' => $availableRoomsCount,
                    'required_guests' => $total_guests
                ]);
                
                return $availableRoomsCount > 0;
            }
            
            // Room type not in booked list, so it's available
            $activeRoomNumbers = $roomType->room->room_numbers->where('status', 'Active');
            
            Log::debug('Room availability check:', [
                'room_type' => $roomType->name,
                'active_rooms' => $activeRoomNumbers->count(),
                'available_rooms' => $activeRoomNumbers->count(),
                'required_guests' => $total_guests,
                'booked_room_ids' => []
            ]);
            
            return $activeRoomNumbers->isNotEmpty();
        });

        // Check promo code and calculate discounts
        $promoCode = null;
        if ($request->promo_code) {
            // First, check if the promo code exists and is active
            $promoCode = PromoCode::where('code', $request->promo_code)
                ->where('is_active', true)
                ->first();
                
            if ($promoCode) {
                // Now verify the dates - promo must be valid for ALL days of the stay
                $stayStartDate = $check_in->format('Y-m-d');
                $stayEndDate = Carbon::parse($request->check_out)->format('Y-m-d');
                
                $promoStartDate = Carbon::parse($promoCode->start_date)->format('Y-m-d');
                $promoEndDate = Carbon::parse($promoCode->end_date)->format('Y-m-d');
                
                // Check if the stay period falls entirely within the promo period
                $isPromoValid = ($stayStartDate >= $promoStartDate && $stayEndDate <= $promoEndDate);
                
                if (!$isPromoValid) {
                    // If the promo is not valid for these dates, set it to null
                    $promoCode = null;
                } else {
                    // For each room type, calculate and set the promo discount if applicable
                    foreach ($availableRoomTypes as $roomType) {
                        if ($roomType->room) {
                            // Check if promo code applies to this room (if it's tied to specific rooms)
                            $applyPromo = $promoCode->rooms->isEmpty() || $promoCode->rooms->contains($roomType->room);
                            
                            if ($applyPromo) {
                                $roomType->room->promo_discount = $promoCode->calculateDiscount($roomType->room->price);
                            }
                        }
                    }
                }
            }
        }

        // Set has_available_rooms based on whether we have available rooms
        $has_available_rooms = $availableRoomTypes->isNotEmpty();
        
        // Set selected_hotel for view
        $selected_hotel = $request->hotel_slug;

        // Add additional debugging for Villa Fiji 3 Pool
        $villaFijiRoomType = $roomTypes->first(function($roomType) {
            return $roomType->name === 'Villa Fiji 3 Pool';
        });
        
        if ($villaFijiRoomType) {
            $roomId = $villaFijiRoomType->room->id;
            
            // Get direct booked dates for this room
            $villaFijiBookedDates = RoomBookedDate::where('room_id', $roomId)
                ->whereIn('book_date', $dateStringsDB)
                ->get(['booking_id', 'room_number_id', 'book_date']);
            
            // Get all bookings for this room ID
            $roomBookings = $bookingsByRoomId[$roomId] ?? collect([]);
            
            // Get unique booked room numbers (excluding nulls)
            $bookedRoomNumberIds = $roomBookings->pluck('room_number_id')
                ->filter() // Remove null values
                ->unique()
                ->toArray();
            
            // Count bookings with null room_number_id
            $nullRoomNumberBookings = $roomBookings->whereNull('room_number_id')
                ->pluck('booking_id')
                ->unique()
                ->count();
                
            Log::debug('Special check for Villa Fiji 3 Pool', [
                'found_in_available' => $availableRoomTypes->contains($villaFijiRoomType),
                'booked_room_numbers' => $bookedRoomNumberIds,
                'null_room_bookings' => $nullRoomNumberBookings,
                'total_active_rooms' => $villaFijiRoomType->room->room_numbers->where('status', 'Active')->count(),
                'direct_booked_dates' => $villaFijiBookedDates->toArray(),
                'date_format_in_search' => $dateStringsDB
            ]);
        }

        return view('frontend.room.room_reservation', [
            'rooms' => Room::withCount('room_numbers')->where('status', 1)->get(),
            'hotels' => $hotels,
            'roomTypes' => $availableRoomTypes->values(), // Use values() to reset array keys
            'has_available_rooms' => $has_available_rooms,
            'selected_hotel' => $selected_hotel,
            'total_guests' => $total_guests,
            'promo_code' => $promoCode, // Pass the promo code to the view
            'form_data' => [
                'hotel_slug' => $request->hotel_slug,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'adults' => $request->adults,
                'children' => $request->children,
                'promo_code' => $request->promo_code,
                'total_guests' => $total_guests
            ]
        ]);
    }

    public function RoomReservation(Request $request)
    {
        // Fetch all hotels for the dropdown
        $hotels = Hotel::all();
        
        // Check if hotel parameter is passed (from hotel group pages)
        $selectedHotelSlug = $request->hotel ?? $request->hotel_slug ?? '';
        
        // Get room types with their relationships
        $roomTypesQuery = RoomType::with([
            'room.facilities', 
            'room.multiImages', 
            'hotel',
            'room.room_numbers'
        ]);

        // If hotel is specified, filter room types by hotel
        if ($selectedHotelSlug) {
            $roomTypesQuery->whereHas('hotel', function($query) use ($selectedHotelSlug) {
                $query->where('slug', $selectedHotelSlug);
            });
            
            Log::debug('Filtering room types by hotel:', ['hotel_slug' => $selectedHotelSlug]);
        }
        
        $roomTypes = $roomTypesQuery->get();
        
        Log::debug('Room Reservation - Room Types Count:', [
            'total_count' => $roomTypes->count(),
            'hotel_filter' => $selectedHotelSlug
        ]);
        
        // Set empty check_date_booking_ids for initial load
        $check_date_booking_ids = [];
        $rooms = Room::withCount('room_numbers')->where('status', 1)->get();
        
        // Initialize form_data with default values or from request
        $form_data = $request->session()->get('reservation_data', [
            'hotel_slug' => $selectedHotelSlug,
            'check_in' => $request->check_in ?? '',
            'check_out' => $request->check_out ?? '',
            'adults' => $request->adults ?? 1,
            'children' => $request->children ?? 0,
            'promo_code' => $request->promo_code ?? '',
            'total_guests' => ((int)($request->adults ?? 1)) + ((int)($request->children ?? 0))
        ]);
        
        Log::debug('Room Reservation - Form Data:', $form_data);
        
        return view('frontend.room.room_reservation', compact(
            'hotels', 
            'roomTypes', 
            'check_date_booking_ids', 
            'rooms'
        ))->with([
            'form_data' => $form_data,
            'selected_hotel' => $selectedHotelSlug
        ]);
    }

    // NEW METHOD: Handle hotel group page redirects
    public function HotelRoomReservation(Request $request, $hotelSlug)
    {
        // Validate that the hotel exists
        $hotel = Hotel::where('slug', $hotelSlug)->first();
        
        if (!$hotel) {
            $notification = [
                'message' => 'Hotel not found',
                'alert-type' => 'error'
            ];
            return redirect()->route('room.reservation')->with($notification);
        }
        
        // Redirect to room reservation with hotel parameter
        return redirect()->route('room.reservation', ['hotel' => $hotelSlug]);
    }

    public function RoomPackage(Request $request)
    {
        $validated = $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'integer|min:0',
            'promo_code' => 'nullable|string'
        ]);
        
        $roomType = RoomType::with(['room', 'room.facilities', 'room.multiImages', 'hotel'])
            ->findOrFail($validated['room_type_id']);
        
        // Calculate nights
        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $nights = $checkIn->diffInDays($checkOut);
        
        // Check promo code
        $promoCode = null;
        $discountAmount = 0;
        
        if ($request->promo_code) {
            $promoCode = PromoCode::where('code', $request->promo_code)
                ->where('is_active', true)
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->first();
                
            // Verify the dates - promo must be valid for ALL days of the stay
            if ($promoCode) {
                $stayStartDate = $checkIn->format('Y-m-d');
                $stayEndDate = $checkOut->format('Y-m-d');
                
                $promoStartDate = Carbon::parse($promoCode->start_date)->format('Y-m-d');
                $promoEndDate = Carbon::parse($promoCode->end_date)->format('Y-m-d');
                
                // Check if the stay period falls entirely within the promo period
                $isPromoValid = ($stayStartDate >= $promoStartDate && $stayEndDate <= $promoEndDate);
                
                if (!$isPromoValid) {
                    // If the promo is not valid for these dates, set it to null
                    $promoCode = null;
                } else if ($promoCode) {
                    // Check if promo code applies to this room
                    $applyPromo = $promoCode->rooms->isEmpty() || $promoCode->rooms->contains($roomType->room);
                    
                    if ($applyPromo) {
                        $discountAmount = $promoCode->calculateDiscount($roomType->room->price);
                    } else {
                        $promoCode = null;
                    }
                }
            }
        }
        
        // Get available room packages
        $roomPackages = RoomPackage::where('status', true)->get();
        
        // Calculate pricing for each package
        foreach ($roomPackages as $package) {
            $package->original_price = $roomType->room->price;
            $package->discount = $discountAmount;
            $package->final_price = max(0, $roomType->room->price - $discountAmount + $package->price_adjustment);
        }
        
        return view('frontend.room.room_package', [
            'roomType' => $roomType,
            'nights' => $nights,
            'promoCode' => $promoCode,
            'discountAmount' => $discountAmount,
            'roomPackages' => $roomPackages,
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'adults' => $validated['adults'],
            'children' => $validated['children'] ?? 0,
            'promo_code' => $request->promo_code,
        ]);
    }

    // Add new method to handle addon selection after package selection
    public function roomAddons(Request $request)
    {
        $validated = $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'package_type' => 'required|string|exists:room_packages,code',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'integer|min:0',
            'promo_code' => 'nullable|string'
        ]);
        
        $roomType = RoomType::with(['room', 'hotel'])->findOrFail($validated['room_type_id']);
        $package = RoomPackage::where('code', $validated['package_type'])->firstOrFail();
        
        // The rest of your logic for the addons page
        
        return view('frontend.booking.room_addons', [
            'roomType' => $roomType,
            'package' => $package,
            // Other required data
        ]);
    }
}