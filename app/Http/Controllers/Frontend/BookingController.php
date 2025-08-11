<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomType;
use App\Models\Booking;
use App\Models\RoomBookedDate;
use App\Models\RoomNumber;
use App\Models\BookingRoomList;
use App\Models\PromoCode;
use App\Models\RoomAddOns; 
use App\Models\RoomPackage;
use App\Models\Room;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmation;



class BookingController extends Controller
{
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
    $package = RoomPackage::with('addons')->where('code', $validated['package_type'])->firstOrFail();
    
    // Calculate nights
    $checkIn = Carbon::parse($validated['check_in']);
    $checkOut = Carbon::parse($validated['check_out']);
    $nights = $checkIn->diffInDays($checkOut);
            
    // Check promo code
    $promoCode = null;
    $discountAmount = 0;
    
    if ($request->promo_code) {
        // Promo code handling with full validation
        $promoCode = PromoCode::where('code', $request->promo_code)
            ->where('is_active', true)
            ->first();
            
        if ($promoCode) {
            // Verify the dates - promo must be valid for ALL days of the stay
            $stayStartDate = $checkIn->format('Y-m-d');
            $stayEndDate = $checkOut->format('Y-m-d');
            
            $promoStartDate = Carbon::parse($promoCode->start_date)->format('Y-m-d');
            $promoEndDate = Carbon::parse($promoCode->end_date)->format('Y-m-d');
            
            // Check if the stay period falls entirely within the promo period
            $isPromoValid = ($stayStartDate >= $promoStartDate && $stayEndDate <= $promoEndDate);
            
            if (!$isPromoValid) {
                // If the promo is not valid for these dates, set it to null
                $promoCode = null;
            } else {
                // Check if promo code applies to this room
                $applyPromo = $promoCode->rooms->isEmpty() || $promoCode->rooms->contains($roomType->room);
                
                if ($applyPromo) {
                    // Calculate discount based on promo code type
                    $discountAmount = $promoCode->calculateDiscount($roomType->room->price);
                    
                    // Log the discount for debugging
                    Log::info('Applied promo code discount:', [
                        'promo_code' => $promoCode->code,
                        'original_price' => $roomType->room->price,
                        'discount_amount' => $discountAmount
                    ]);
                } else {
                    $promoCode = null;
                }
            }
        }
    } else {
        // If no promo code provided, check if the room has a discount
        $discountAmount = $roomType->room->discount ?? 0;
    }
    
    // Calculate base pricing
    $roomPrice = $roomType->room->price;
    $packageAdjustment = $package->price_adjustment;
    $totalDiscount = $discountAmount;  // Apply only the promo discount, not combining with room discount
    
    $pricePerNight = $roomPrice + $packageAdjustment - $totalDiscount;
    $baseTotal = $pricePerNight * $nights;
    
    // Get selected addons from session
    $selectedAddons = session('selected_addons', []);
    
    // Calculate addon prices
    $addonTotal = 0;
    foreach ($selectedAddons as $addonId => $quantity) {
        $addon = RoomAddOns::find($addonId);
        if ($addon) {
            if ($addon->price_type == 'per_night') {
                $addonTotal += $addon->price * $quantity * $nights;
            } else {
                $addonTotal += $addon->price * $quantity;
            }
        }
    }
    
    // Calculate total price
    $totalPrice = $baseTotal + $addonTotal;
    
    // Store in session
    session([
        'booking_total_price' => $totalPrice,
        'promo_discount' => $discountAmount,  // Store promo discount in session
        'original_room_price' => $roomPrice   // Store original price for comparison
    ]);
    
    // Get all addons by category
    $addonsByCategory = RoomAddOns::where('status', true)
        ->orderBy('category')
        ->orderBy('name')
        ->get()
        ->groupBy('category');
        
    // Get the IDs of addons included in the selected package
    $includedAddonIds = $package->addons->pluck('id')->toArray();
    
    return view('frontend.booking.room_addons', [
        'roomType' => $roomType,
        'package' => $package,
        'nights' => $nights,
        'promoCode' => $promoCode,
        'discountAmount' => $discountAmount,
        'roomPrice' => $roomPrice,
        'packagePrice' => $packageAdjustment,
        'totalDiscount' => $totalDiscount,
        'pricePerNight' => $pricePerNight,
        'baseTotal' => $baseTotal,
        'addonTotal' => $addonTotal,
        'totalPrice' => $totalPrice,
        'addonsByCategory' => $addonsByCategory,
        'includedAddonIds' => $includedAddonIds,
        'check_in' => $validated['check_in'],
        'check_out' => $validated['check_out'],
        'adults' => $validated['adults'],
        'children' => $validated['children'] ?? 0,
        'promo_code' => $request->promo_code,
    ]);
}
    
    public function addAddon(Request $request)
{
    $request->validate([
        'addon_id' => 'required|exists:room_add_ons,id',
        'room_type_id' => 'required|exists:room_types,id',
        'package_type' => 'required|string|exists:room_packages,code',
        'check_in' => 'required|date',
        'check_out' => 'required|date|after:check_in',
        'adults' => 'required|integer|min:1',
        'children' => 'integer|min:0',
        'promo_code' => 'nullable|string'
    ]);
    
    // Get selected addons from session or initialize empty array
    $selectedAddons = session('selected_addons', []);
    
    // Get addon details to check if it's already in a package
    $addon = RoomAddOns::find($request->addon_id);
    $package = RoomPackage::where('code', $request->package_type)->first();
    
    // Check if addon is already included in the package
    $isIncluded = $package && $package->addons->contains($request->addon_id);
    
    if (!$isIncluded) {
        // Add or increment the selected addon
        $selectedAddons[$request->addon_id] = isset($selectedAddons[$request->addon_id]) 
            ? $selectedAddons[$request->addon_id] + 1 
            : 1;
        
        // Store updated addons in session
        session(['selected_addons' => $selectedAddons]);
        
        // Update total price in session
        $this->updateTotalPrice(
            $request->room_type_id, 
            $request->package_type, 
            $request->check_in, 
            $request->check_out, 
            $selectedAddons,
            $request->promo_code  // Pass promo code to keep it consistent
        );
    }
    
    // Redirect back to addons page with success message
    return redirect()->route('room.addons', [
        'room_type_id' => $request->room_type_id,
        'package_type' => $request->package_type,
        'check_in' => $request->check_in,
        'check_out' => $request->check_out,
        'adults' => $request->adults,
        'children' => $request->children,
        'promo_code' => $request->promo_code
    ])->with('success', 'Service added to your booking');
}

/**
 * Helper function to update total price in session
 */
private function updateTotalPrice($roomTypeId, $packageType, $checkIn, $checkOut, $selectedAddons, $promoCodeStr = null)
{
    $roomType = RoomType::with('room')->findOrFail($roomTypeId);
    $package = RoomPackage::where('code', $packageType)->firstOrFail();
    
    // Calculate nights
    $checkInDate = Carbon::parse($checkIn);
    $checkOutDate = Carbon::parse($checkOut);
    $nights = $checkInDate->diffInDays($checkOutDate);
    
    // Base room price
    $roomPrice = $roomType->room->price;
    $packageAdjustment = $package->price_adjustment;
    
    // Calculate promo discount if applicable
    $discountAmount = 0;
    
    if ($promoCodeStr) {
        $promoCode = PromoCode::where('code', $promoCodeStr)
            ->where('is_active', true)
            ->first();
            
        if ($promoCode) {
            // Verify dates and applicability to this room
            $stayStartDate = $checkInDate->format('Y-m-d');
            $stayEndDate = $checkOutDate->format('Y-m-d');
            
            $promoStartDate = Carbon::parse($promoCode->start_date)->format('Y-m-d');
            $promoEndDate = Carbon::parse($promoCode->end_date)->format('Y-m-d');
            
            $isPromoValid = ($stayStartDate >= $promoStartDate && $stayEndDate <= $promoEndDate);
            
            if ($isPromoValid) {
                $applyPromo = $promoCode->rooms->isEmpty() || $promoCode->rooms->contains($roomType->room);
                
                if ($applyPromo) {
                    $discountAmount = $promoCode->calculateDiscount($roomType->room->price);
                }
            }
        }
    } else {
        // Use existing discount from session if available
        $discountAmount = session('promo_discount', 0);
    }
    
    // Calculate base total price (room + package - discount)
    $pricePerNight = $roomPrice + $packageAdjustment - $discountAmount;
    $baseTotal = $pricePerNight * $nights;
    
    // Add addon prices
    $addonTotal = 0;
    foreach ($selectedAddons as $addonId => $quantity) {
        $addon = RoomAddOns::find($addonId);
        if ($addon) {
            if ($addon->price_type == 'per_night') {
                $addonTotal += $addon->price * $quantity * $nights;
            } else {
                $addonTotal += $addon->price * $quantity;
            }
        }
    }
    
    // Calculate final total
    $totalPrice = $baseTotal + $addonTotal;
    
    // Store in session
    session([
        'booking_total_price' => $totalPrice,
        'promo_discount' => $discountAmount
    ]);
    
    return $totalPrice;
}

    public function updateAddon(Request $request)
{
    $request->validate([
        'addon_id' => 'required|exists:room_add_ons,id',
        'room_type_id' => 'required|exists:room_types,id',
        'package_type' => 'required|string|exists:room_packages,code',
        'check_in' => 'required|date',
        'check_out' => 'required|date|after:check_in',
        'adults' => 'required|integer|min:1',
        'children' => 'integer|min:0',
        'promo_code' => 'nullable|string',
        'quantity' => 'required|integer|min:0'
    ]);
    
    // Get selected addons from session or initialize empty array
    $selectedAddons = session('selected_addons', []);
    
    // Update quantity or remove if quantity is 0
    if ($request->quantity > 0) {
        $selectedAddons[$request->addon_id] = $request->quantity;
    } else {
        unset($selectedAddons[$request->addon_id]);
    }
    
    // Store updated addons in session
    session(['selected_addons' => $selectedAddons]);
    
    // Update total price in session, passing the promo code
    $this->updateTotalPrice(
        $request->room_type_id, 
        $request->package_type, 
        $request->check_in, 
        $request->check_out, 
        $selectedAddons,
        $request->promo_code
    );
    
    return redirect()->route('room.addons', [
        'room_type_id' => $request->room_type_id,
        'package_type' => $request->package_type,
        'check_in' => $request->check_in,
        'check_out' => $request->check_out,
        'adults' => $request->adults,
        'children' => $request->children,
        'promo_code' => $request->promo_code
    ])->with('success', 'Service quantity updated');
}
    
    /**
 * Display the booking details form
 */
    public function bookingDetails(Request $request)
    {
        $validated = $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'package_type' => 'required|string|exists:room_packages,code',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'integer|min:0',
            'promo_code' => 'nullable|string',
        ]);
        
        $roomType = RoomType::with(['room', 'room.facilities', 'hotel'])->findOrFail($validated['room_type_id']);
        $package = RoomPackage::with('addons')->where('code', $validated['package_type'])->firstOrFail();
        
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
                ->first();
                
            if ($promoCode) {
                // Verify the dates - promo must be valid for ALL days of the stay
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
        
        // Calculate base pricing
        $roomPrice = $roomType->room->price;
        $packageAdjustment = $package->price_adjustment;
        $totalDiscount = $discountAmount + $roomType->room->discount;
        
        $pricePerNight = $roomPrice + $packageAdjustment - $totalDiscount;
        $baseTotal = $pricePerNight * $nights;
        
        // Get selected addons from session
        $selectedAddons = session('selected_addons', []);
        
        // Calculate addon prices
        $addonTotal = 0;
        foreach ($selectedAddons as $addonId => $quantity) {
            $addon = RoomAddOns::find($addonId);
            if ($addon) {
                if ($addon->price_type == 'per_night') {
                    $addonTotal += $addon->price * $quantity * $nights;
                } else {
                    $addonTotal += $addon->price * $quantity;
                }
            }
        }
        
        // Calculate total price
        $totalPrice = $baseTotal + $addonTotal;
        
        // Store in session
        session(['booking_total_price' => $totalPrice]);
        
        return view('frontend.booking.details', [
            'roomType' => $roomType,
            'package' => $package,
            'nights' => $nights,
            'promoCode' => $promoCode,
            'discountAmount' => $discountAmount,
            'roomPrice' => $roomPrice,
            'packagePrice' => $packageAdjustment,
            'totalDiscount' => $totalDiscount,
            'pricePerNight' => $pricePerNight,
            'baseTotal' => $baseTotal,
            'addonTotal' => $addonTotal,
            'totalPrice' => $totalPrice,
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'adults' => $validated['adults'],
            'children' => $validated['children'] ?? 0,
            'promo_code' => $request->promo_code,
        ]);
    }

public function createBooking(Request $request)
{
    // Log initial method call
    Log::info('createBooking method called', $request->all());
    
    try {
        // Validate request dengan validasi yang lebih ketat
        $validated = $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'package_type' => 'required|string|exists:room_packages,code',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'integer|min:0',
            'promo_code' => 'nullable|string',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255', // Diubah menjadi nullable
            'email' => 'required|email:rfc,dns|max:255', // Validasi email lebih ketat
            'phone' => 'required|regex:/^[0-9\+\-\(\) ]+$/|max:20', // Hanya menerima angka, +, -, (, ), dan spasi
            'country' => 'nullable|string|max:100',
            'additional_request' => 'nullable|string',
            'consent_marketing' => 'nullable|string',
            'payment_method' => 'required|string|in:xendit,manual_transfer,pay_at_hotel',
        ], [
            // Custom error messages
            'phone.regex' => 'The phone number must contain only numbers and the symbols +, -, (, ).',
            'email.email' => 'Please enter a valid email address (e.g., name@example.com).',
            'first_name.required' => 'First name is required.',
            'payment_method.required' => 'Please select a payment method.',
            'email.required' => 'Email address is required.',
            'phone.required' => 'Phone number is required.'
        ]);
        
        Log::info('Validation passed in createBooking');
        
        // Get room type and package
        $roomType = RoomType::with('room.room_numbers')->findOrFail($request->room_type_id);
        $package = RoomPackage::where('code', $request->package_type)->firstOrFail();

        Log::info('Room Type Data', [
            'id' => $roomType->id,
            'name' => $roomType->name,
            'room_id' => $roomType->room->id ?? 'No room'
        ]);
        
        // Calculate nights
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $nights = $checkIn->diffInDays($checkOut);
        
        // Prepare date strings for availability check - IMPORTANT: Use database format (Y-m-d)
        $dateRange = CarbonPeriod::create($checkIn, $checkOut->copy()->subDay());
        $dateStringsDisplay = []; // For display (d-m-Y)
        $dateStringsDB = []; // For database (Y-m-d)
        
        foreach ($dateRange as $date) {
            $dateStringsDisplay[] = $date->format('d-m-Y');
            $dateStringsDB[] = $date->format('Y-m-d');
        }
        
        Log::info('Date strings for booking', [
            'display_dates' => $dateStringsDisplay,
            'db_dates' => $dateStringsDB,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'nights' => $nights
        ]);
        
        // Get total guests
        $total_guests = (int)$request->adults + (int)$request->children;
        
        // Get all booked room numbers for these dates from both tables
        $bookedRoomNumberIds = [];
        
        // Get from room_booked_dates - use database date format
        $bookedDates = RoomBookedDate::whereIn('book_date', $dateStringsDB)
            ->where('room_id', $roomType->room->id)
            ->pluck('room_number_id')
            ->toArray();
            
        $bookedRoomNumberIds = array_merge($bookedRoomNumberIds, $bookedDates);
        
        // Get from booking_room_lists through bookings and booked dates
        $bookedLists = BookingRoomList::where('room_id', $roomType->room->id)
            ->whereHas('booking.booked_dates', function($query) use ($dateStringsDB) {
                $query->whereIn('book_date', $dateStringsDB);
            })
            ->pluck('room_number_id')
            ->toArray();
            
        $bookedRoomNumberIds = array_merge($bookedRoomNumberIds, $bookedLists);
        
        // Filter out null values
        $bookedRoomNumberIds = array_filter($bookedRoomNumberIds, function($value) {
            return $value !== null;
        });
        
        $bookedRoomNumberIds = array_unique($bookedRoomNumberIds);
        
        // Count bookings with null room numbers
        $nullRoomBookings = RoomBookedDate::whereIn('book_date', $dateStringsDB)
            ->where('room_id', $roomType->room->id)
            ->whereNull('room_number_id')
            ->distinct('booking_id')
            ->count('booking_id');
        
        Log::info('Booked room numbers', [
            'room_id' => $roomType->room->id,
            'dates' => $dateStringsDB,
            'booked_ids' => $bookedRoomNumberIds,
            'null_room_bookings' => $nullRoomBookings
        ]);
        
        // Count active room numbers
        $activeRoomNumbersCount = RoomNumber::where('rooms_id', $roomType->room->id)
            ->where('status', 'Active')
            ->count();
            
        // Calculate how many room numbers are available
        $bookedRoomsCount = count($bookedRoomNumberIds) + $nullRoomBookings;
        $availableRoomsCount = $activeRoomNumbersCount - $bookedRoomsCount;
        
        if ($availableRoomsCount <= 0) {
            Log::warning('All room numbers are already booked for these dates', [
                'room_type' => $roomType->name,
                'dates' => $dateStringsDB,
                'active_rooms' => $activeRoomNumbersCount,
                'booked_rooms' => $bookedRoomsCount
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Sorry, this room is fully booked for the selected dates. Please choose different dates or another room.');
        }
            
        // Find available room number considering capacity and existing bookings
        $availableRoomNumber = RoomNumber::where('rooms_id', $roomType->room->id)
            ->where('status', 'Active')
            ->where(function($query) use ($total_guests) {
                $query->whereNull('capacity')
                    ->orWhere('capacity', '>=', $total_guests);
            })
            ->whereNotIn('id', $bookedRoomNumberIds)
            ->first();
            
        if (!$availableRoomNumber) {
            // Get any active room number as a fallback
            $availableRoomNumber = RoomNumber::where('rooms_id', $roomType->room->id)
                ->where('status', 'Active')
                ->first();
                
            if (!$availableRoomNumber) {
                Log::warning('No available room found for booking and no active room numbers exist', [
                    'room_type' => $roomType->name,
                    'dates' => $dateStringsDB
                ]);
                
                return back()
                    ->withInput()
                    ->with('error', 'Sorry, this room is no longer available for the selected dates. Please choose different dates or another room.');
            }
        }
        
        Log::info('Found available room number', [
            'room_number_id' => $availableRoomNumber->id,
            'room_no' => $availableRoomNumber->room_no,
            'capacity' => $availableRoomNumber->capacity ?? $roomType->room->guests_total
        ]);
        
        // Process promo code if provided
        $promoDiscount = 0;
        $promoCode = null;
        
        if ($request->promo_code) {
            // Promo code processing code...
            $promoCode = PromoCode::where('code', $request->promo_code)
                ->where('is_active', true)
                ->first();
                
            if ($promoCode) {
                // Verify dates and apply discount
                $stayStartDate = $checkIn->format('Y-m-d');
                $stayEndDate = $checkOut->format('Y-m-d');
                
                $promoStartDate = Carbon::parse($promoCode->start_date)->format('Y-m-d');
                $promoEndDate = Carbon::parse($promoCode->end_date)->format('Y-m-d');
                
                $isPromoValid = ($stayStartDate >= $promoStartDate && $stayEndDate <= $promoEndDate);
                
                if ($isPromoValid) {
                    $applyPromo = $promoCode->rooms->isEmpty() || $promoCode->rooms->contains($roomType->room);
                    
                    if ($applyPromo) {
                        $promoDiscount = $promoCode->calculateDiscount($roomType->room->price);
                    }
                }
            }
        }
        
        // Calculate total price
        $roomPrice = $roomType->room->price;
        $packageAdjustment = $package->price_adjustment;
        $totalDiscount = $promoDiscount;
        
        $pricePerNight = $roomPrice + $packageAdjustment - $totalDiscount;
        $baseTotal = $pricePerNight * $nights;
        
        // Calculate addon prices
        $selectedAddons = session('selected_addons', []);
        $addonTotal = 0;
        $addonItems = []; // Array to store addon details
        
        foreach ($selectedAddons as $addonId => $quantity) {
            $addon = RoomAddOns::find($addonId);
            if ($addon) {
                if ($addon->price_type == 'per_night') {
                    $addonPrice = $addon->price * $quantity * $nights;
                } else {
                    $addonPrice = $addon->price * $quantity;
                }
                
                $addonTotal += $addonPrice;
                
                // Save addon details for later
                $addonItems[] = [
                    'id' => $addon->id,
                    'name' => $addon->name,
                    'quantity' => $quantity,
                    'price' => $addon->price,
                    'price_type' => $addon->price_type,
                    'total_price' => $addonPrice
                ];
            }
        }
        
        // Save addon items to session for use in other pages
        Session::put('addon_items', $addonItems);
        Session::put('addon_total', $addonTotal);
        
        // Calculate final total price
        $totalPrice = $baseTotal + $addonTotal;
        
        // Generate a unique booking code
        $bookingCode = 'BK-' . strtoupper(Str::random(8));
        
        // Save promo code ID if applicable
        $promoCodeId = $promoCode ? $promoCode->id : null;
        
        // Set initial status based on payment method
        $initialStatus = ($request->payment_method == 'pay_at_hotel') ? 'confirmed' : 'pending';
        
        // Create the booking data array
        $bookingData = [
            'hotel_id' => $roomType->hotel_id,
            'rooms_id' => $roomType->room->id,
            'room_type_id' => $roomType->id,
            'room_type_name' => $roomType->name,
            'package_id' => $package->id,
            'addon_total' => $addonTotal, 
            'promo_code_id' => $promoCodeId,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'adults' => $request->adults,
            'child' => $request->children ?? 0,
            'total_night' => $nights,
            'actual_price' => $roomPrice * $nights,
            'package_price' => $packageAdjustment * $nights,
            'subtotal' => ($roomPrice + $packageAdjustment) * $nights,
            'discount' => $totalDiscount * $nights,
            'total_amount' => $totalPrice,
            'payment_method' => $request->payment_method ?? 'xendit',
            'payment_status' => ($request->payment_method == 'pay_at_hotel') ? 'pending_verification' : 'pending',
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'country' => $request->country,
            'additional_request' => $request->additional_request,
            'code' => $bookingCode,
            'status' => $initialStatus,
            'consent_marketing' => $request->has('consent_marketing') ? true : false,
        ];
        
        // Check if user_id column exists in bookings table
        if (Schema::hasColumn('bookings', 'user_id')) {
            $bookingData['user_id'] = Auth::check() ? Auth::id() : null;
        }
        
        Session::put('room_type_name', $roomType->name);
        
        // Use transaction to ensure data consistency
        DB::beginTransaction();
        
        try {
            // Create the booking
            $booking = Booking::create($bookingData);
            
            Log::info('Booking created successfully', [
                'booking_id' => $booking->id,
                'code' => $booking->code
            ]);

            foreach ($dateStringsDB as $dateString) {
                RoomBookedDate::create([
                    'booking_id' => $booking->id,
                    'room_id' => $roomType->room->id,
                    'room_number_id' => $availableRoomNumber->id,
                    'book_date' => $dateString,
                ]);
                
                Log::debug('Created booked date:', [
                    'booking_id' => $booking->id,
                    'room_id' => $roomType->room->id,
                    'room_number_id' => $availableRoomNumber->id,
                    'date' => $dateString
                ]);
            }
            
            // Create booking room list entry
            BookingRoomList::create([
                'booking_id' => $booking->id,
                'room_id' => $roomType->room->id,
                'room_number_id' => $availableRoomNumber->id, 
            ]);
            
            Log::info('Assigned room number to booking', [
                'booking_id' => $booking->id,
                'room_number_id' => $availableRoomNumber->id
            ]);
            
            // Add selected addons to booking
            if (!empty($selectedAddons)) {
                $bookingAddons = [];
                
                foreach ($selectedAddons as $addonId => $quantity) {
                    $addon = RoomAddOns::find($addonId);
                    if ($addon) {
                        $addonPrice = $addon->price;
                        $totalAddonPrice = $addon->price_type == 'per_night' 
                            ? $addonPrice * $quantity * $nights 
                            : $addonPrice * $quantity;
                        
                        $bookingAddons[] = [
                            'booking_id' => $booking->id,
                            'room_add_ons_id' => $addonId,
                            'quantity' => $quantity,
                            'price' => $addonPrice,
                            'total_price' => $totalAddonPrice,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                
                // Insert booking addons if any
                if (!empty($bookingAddons)) {
                    DB::table('booking_room_add_ons')->insert($bookingAddons);
                }
            }
            
            // Increment promo code usage counter if used
            if ($promoCode) {
                $promoCode->increment('used_count');
            }
            
            // Commit transaction
            DB::commit();
            
            // Clear selected addons from session
            session()->forget('selected_addons');
            
            // Store booking ID in session for payment
            Session::put('booking_id', $booking->id);
            Session::put('booking_code', $booking->code);
            
            // Send confirmation email for Pay at Hotel
            if ($request->payment_method == 'pay_at_hotel') {
                $this->sendBookingConfirmationEmail($booking);
            }
            
            Log::info('About to redirect to payment page', [
                'payment_method' => $request->payment_method,
                'redirect_url' => route('booking.payment.xendit', ['code' => $booking->code])
            ]);

            // Redirect based on payment method
            if ($request->payment_method == 'xendit') {
                return redirect()->route('booking.payment.xendit', ['code' => $booking->code]);
            } else if ($request->payment_method == 'manual_transfer') {
                return redirect()->route('booking.manual_transfer', ['code' => $booking->code]);
            } else if ($request->payment_method == 'pay_at_hotel') {
                return redirect()->route('booking.confirmation', ['code' => $booking->code])
                    ->with('success', 'Your booking is confirmed! Payment will be processed at the hotel.');
            } else {
                // Default to payment page
                return redirect()->route('booking.payment', ['code' => $booking->code]);
            }
            
        } catch (\Exception $e) {
            // Rollback transaction if error occurs
            DB::rollBack();
            throw $e;
        } return back()
            ->withErrors($e->errors())
            ->withInput()
            ->with('error_alert', 'Please fix the errors in the form before proceeding.');
        
    } catch (\Exception $e) {
        // Log error for debugging
        Log::error('Exception in createBooking method', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
        
        // Display error message to user
        return back()
            ->withInput()
            ->with('error', 'An error occurred while creating your booking: ' . $e->getMessage());
    }
}
        
        // Send booking confirmation email 
        private function sendBookingConfirmationEmail(Booking $booking)
    {
        try {
            // Load relationships if they don't exist
            if (!$booking->relationLoaded('room')) {
                $booking->load(['room', 'package', 'hotel']);
            }
            
            // Send email
            Mail::to($booking->email)
                ->bcc('reservations@tanjunglesung.com') // BCC to reservation team
                ->send(new BookingConfirmation($booking));
            
            // Log success
            Log::info('Booking confirmation email sent successfully', [
                'booking_code' => $booking->code,
                'email' => $booking->email
            ]);
            
            return true;
        } catch (\Exception $e) {
            // Log error
            Log::error('Failed to send booking confirmation email: ' . $e->getMessage(), [
                'booking_code' => $booking->code,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }

    public function showPaymentOptions($code)
{
    $booking = Booking::with(['room', 'package', 'addons', 'hotel'])
        ->where('code', $code)
        ->firstOrFail();
    
    // Check if booking belongs to authenticated user if logged in
    if (Auth::check() && $booking->user_id && $booking->user_id != Auth::id()) {
        abort(403, 'Unauthorized access to booking.');
    }
    
    // Check if booking is already paid
    if ($booking->payment_status == 'paid') {
        return redirect()->route('booking.confirmation', ['code' => $booking->code])
            ->with('message', 'This booking has already been paid.');
    }
    
    // Pass payment methods to view
    $paymentMethods = [
        'xendit' => [
            'name' => 'Online Payment',
            'description' => 'Credit Card, Bank Transfer, E-wallet, QRIS',
            'icon' => 'credit-card'
        ],
        'manual_transfer' => [
            'name' => 'Manual Bank Transfer',
            'description' => 'Transfer manually and upload receipt',
            'icon' => 'university'
        ],
        'pay_at_hotel' => [
            'name' => 'Pay at Hotel',
            'description' => 'Pay during check-in',
            'icon' => 'hotel'
        ]
    ];
    
    return view('frontend.booking.payment', [
        'booking' => $booking,
        'paymentMethods' => $paymentMethods
    ]);
}

public function processPayment(Request $request, $code)
{
    $booking = Booking::where('code', $code)->firstOrFail();
    
    // Validate payment method
    $validated = $request->validate([
        'payment_method' => 'required|string|in:xendit,manual_transfer,pay_at_hotel',
    ]);
    
    // Based on payment method, redirect to appropriate payment page
    switch($request->payment_method) {
        case 'xendit':
            return redirect()->route('booking.payment.xendit', ['code' => $booking->code]);
            
        case 'manual_transfer':
            // Update booking with manual transfer details
            $booking->update([
                'payment_method' => 'manual_transfer',
                'status' => 'pending_payment'
            ]);
            
            return redirect()->route('booking.manual_transfer', ['code' => $booking->code]);
            
        case 'pay_at_hotel':
            // Update booking with pay at hotel details
            $booking->update([
                'payment_method' => 'pay_at_hotel',
                'status' => 'confirmed'
            ]);
            
            return redirect()->route('booking.confirmation', ['code' => $booking->code])
                ->with('success', 'Your booking is confirmed! Payment will be processed at the hotel.');
            
        default:
            return redirect()->back()->with('error', 'Invalid payment method selected.');
    }
}               

            
/**
 * Handle payment callback (for Xendit)
 */
public function handlePaymentCallback(Request $request)
{
    // Verify the callback is from Xendit
    $callbackToken = $request->header('X-CALLBACK-TOKEN');
    if ($callbackToken !== config('services.xendit.callback_token')) {
        Log::warning('Unauthorized Xendit callback attempt', [
            'token_received' => $callbackToken,
            'ip' => $request->ip()
        ]);
        abort(403, 'Unauthorized');
    }
    
    $payload = $request->all();
    Log::info('Xendit callback received', $payload);
    
    // Find booking by external_id
    $booking = Booking::where('code', $payload['external_id'])->first();
    
    if (!$booking) {
        Log::error('Booking not found for callback', [
            'external_id' => $payload['external_id']
        ]);
        return response()->json(['error' => 'Booking not found'], 404);
    }
    
    // Update booking status based on payment status
    if ($payload['status'] === 'PAID' || $payload['status'] === 'COMPLETED') {
        $booking->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
            'transaction_id' => $payload['id'],
        ]);
        
        Log::info('Payment marked as paid', [
            'booking_code' => $booking->code,
            'invoice_id' => $payload['id']
        ]);
        
        // Send booking confirmation email
        $this->sendBookingConfirmationEmail($booking);
    } else if ($payload['status'] === 'EXPIRED' || $payload['status'] === 'FAILED') {
        $booking->update([
            'payment_status' => 'expired',
            'status' => 'cancelled',
        ]);
        
        Log::info('Payment marked as expired/failed', [
            'booking_code' => $booking->code,
            'invoice_id' => $payload['id'],
            'status' => $payload['status']
        ]);
    }
    
    return response()->json(['success' => true]);
}    /**
     * Display booking confirmation page
     */
    public function showConfirmation($code)
    {
        $booking = Booking::with([
            'room', 
            'roomType', 
            'package', 
            'addons', 
            'promoCode', 
            'hotel'
        ])->where('code', $code)
        ->firstOrFail();

        // Get room name correctly
        $roomName = "Standard Room"; // default value
        if ($booking->roomType) {
            $roomName = $booking->roomType->name;
        } elseif ($booking->room) {
            if ($booking->room instanceof \Illuminate\Database\Eloquent\Collection) {
                $roomName = $booking->room->isNotEmpty() ? $booking->room->first()->name : "Standard Room";
            } else {
                $roomName = $booking->room->name ?? "Standard Room";
            }
        }
        
        // Hitung addon total dengan benar - perbaiki cara menghitung total
        $addonTotal = 0;
        foreach ($booking->addons as $addon) {
            $addonTotal += $addon->pivot->total_price;
        }
        
        return view('frontend.booking.confirmation', [
            'booking' => $booking,
            'roomName' => $roomName,
            'addonTotal' => $addonTotal
        ]);
    }
    

    public function printSummary(Request $request)
    {
        $validated = $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'package_type' => 'required|string|exists:room_packages,code',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'integer|min:0',
            'promo_code' => 'nullable|string',
        ]);
        
        $roomType = RoomType::with(['room', 'room.facilities', 'hotel'])->findOrFail($validated['room_type_id']);
        $package = RoomPackage::with('addons')->where('code', $validated['package_type'])->firstOrFail();
        
        // Calculate nights
        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $nights = $checkIn->diffInDays($checkOut);
        
        // Initialize variables
        $promoCode = null;
        $discountAmount = 0;
        $originalRoomPrice = $roomType->room->price;
        
        // Check for promo code
        if ($request->promo_code) {
            // Get promo code from database
            $promoCode = PromoCode::where('code', $request->promo_code)
                ->where('is_active', true)
                ->first();
                
            if ($promoCode) {
                // Verify promo code validity for dates
                $stayStartDate = $checkIn->format('Y-m-d');
                $stayEndDate = $checkOut->format('Y-m-d');
                
                $promoStartDate = Carbon::parse($promoCode->start_date)->format('Y-m-d');
                $promoEndDate = Carbon::parse($promoCode->end_date)->format('Y-m-d');
                
                $isPromoValid = ($stayStartDate >= $promoStartDate && $stayEndDate <= $promoEndDate);
                
                if ($isPromoValid) {
                    // Check if promo applies to this room
                    $applyPromo = $promoCode->rooms->isEmpty() || $promoCode->rooms->contains($roomType->room);
                    
                    if ($applyPromo) {
                        // Calculate discount
                        $discountAmount = $promoCode->calculateDiscount($roomType->room->price);
                        
                        // Debug info
                        Log::info('Promo applied in printSummary', [
                            'promo_code' => $promoCode->code,
                            'original_price' => $originalRoomPrice,
                            'discount_amount' => $discountAmount
                        ]);
                    }
                }
            }
        } else {
            // Check session for discount (from previous steps)
            $discountAmount = session('promo_discount', 0);
            
            if ($discountAmount > 0) {
                // Try to retrieve promo code used in session
                $promoCodeString = session('promo_code');
                if ($promoCodeString) {
                    $promoCode = PromoCode::where('code', $promoCodeString)
                        ->where('is_active', true)
                        ->first();
                }
            }
        }
        
        // Calculate prices with discount
        $roomPrice = $originalRoomPrice;
        $discountedRoomPrice = $roomPrice - $discountAmount;
        $packageAdjustment = $package->price_adjustment;
        
        // Final calculations
        $pricePerNight = $discountedRoomPrice + $packageAdjustment;
        $baseTotal = $pricePerNight * $nights;
        
        // Get selected addons from session
        $selectedAddons = session('selected_addons', []);
        
        // Calculate addon prices
        $addonTotal = 0;
        foreach ($selectedAddons as $addonId => $quantity) {
            $addon = RoomAddOns::find($addonId);
            if ($addon) {
                if ($addon->price_type == 'per_night') {
                    $addonTotal += $addon->price * $quantity * $nights;
                } else {
                    $addonTotal += $addon->price * $quantity;
                }
            }
        }
        
        // Final total price
        $totalPrice = $baseTotal + $addonTotal;
        
        // Debug information
        Log::info('Print Summary Pricing Details', [
            'room_type' => $roomType->name,
            'original_price' => $originalRoomPrice,
            'discount_amount' => $discountAmount,
            'discounted_price' => $discountedRoomPrice,
            'package_adjustment' => $packageAdjustment,
            'price_per_night' => $pricePerNight,
            'nights' => $nights,
            'base_total' => $baseTotal,
            'addon_total' => $addonTotal,
            'total_price' => $totalPrice,
            'promo_code' => $promoCode ? $promoCode->code : 'No promo code'
        ]);
        
        return view('frontend.booking.print_summary', [
            'roomType' => $roomType,
            'package' => $package,
            'nights' => $nights,
            'promoCode' => $promoCode,
            'originalRoomPrice' => $originalRoomPrice, 
            'discountAmount' => $discountAmount, 
            'roomPrice' => $discountedRoomPrice, 
            'packagePrice' => $packageAdjustment,
            'pricePerNight' => $pricePerNight,
            'baseTotal' => $baseTotal,
            'addonTotal' => $addonTotal,
            'totalPrice' => $totalPrice,
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'adults' => $validated['adults'],
            'children' => $validated['children'] ?? 0,
            'promo_code' => $request->promo_code,
        ]);
    }
    
}

    