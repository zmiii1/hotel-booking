<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingManagementController extends Controller
{
    public function dashboard()
    {
        // Business-focused metrics only
        $totalBookings = Booking::where('payment_status', 'paid')->count();
        $todayArrivals = Booking::where('payment_status', 'paid')
            ->whereDate('check_in', Carbon::today())
            ->count();
        $upcomingArrivals = Booking::where('payment_status', 'paid')
            ->where('check_in', '>', Carbon::today())
            ->where('check_in', '<=', Carbon::today()->addDays(7))
            ->count();
        $totalRevenue = Booking::where('payment_status', 'paid')->sum('total_amount');
        
        // Hotel performance
        $bookingsByHotel = Booking::selectRaw('hotel_id, count(*) as total, sum(total_amount) as revenue')
            ->where('payment_status', 'paid')
            ->groupBy('hotel_id')
            ->get()
            ->map(function($item) {
                $hotel = Hotel::find($item->hotel_id);
                return [
                    'hotel_name' => $hotel ? $hotel->name : 'Unknown',
                    'total_bookings' => $item->total,
                    'revenue' => $item->revenue
                ];
            });
        
        // Monthly performance (current year)
        $currentYear = Carbon::now()->year;
        $monthlyBookings = Booking::selectRaw('MONTH(created_at) as month, count(*) as total_bookings, sum(total_amount) as total_revenue')
            ->where('payment_status', 'paid')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->get();
        
        // Chart data
        $chartLabels = [];
        $chartBookingData = [];
        $chartRevenueData = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::create()->month($i)->format('M');
            $chartLabels[] = $monthName;
            
            $monthData = $monthlyBookings->firstWhere('month', $i);
            $chartBookingData[] = $monthData ? $monthData->total_bookings : 0;
            $chartRevenueData[] = $monthData ? $monthData->total_revenue : 0;
        }
        
        // Recent confirmed bookings
        $recentBookings = Booking::with(['hotel', 'roomType'])
            ->where('payment_status', 'paid')
            ->latest()
            ->take(10)
            ->get();
        
        return view('backend.bookings.dashboard', compact(
            'totalBookings',
            'todayArrivals', 
            'upcomingArrivals',
            'totalRevenue',
            'bookingsByHotel',
            'chartLabels',
            'chartBookingData',
            'chartRevenueData',
            'recentBookings'
        ));
    }
    
    public function index(Request $request)
    {
        // SIMPLIFIED: All bookings shown are confirmed (payment completed)
        $query = Booking::with(['hotel', 'roomType', 'package'])
            ->where('payment_status', 'paid'); // Only show successful bookings
        
        // Log for debugging
        $totalConfirmedBookings = $query->count();
        Log::info('Booking Management - Simplified', [
            'total_confirmed_bookings' => $totalConfirmedBookings,
            'applied_filters' => $request->only(['hotel_id', 'date_filter_type', 'date_from', 'date_to', 'search'])
        ]);
        
        // Hotel filter
        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
            Log::info('Applied hotel filter', ['hotel_id' => $request->hotel_id, 'count_after' => $query->count()]);
        }
        
        // BUSINESS FOCUSED: Operational view filters
        if ($request->filled('view_type')) {
            $viewType = $request->view_type;
            
            switch ($viewType) {
                case 'today_arrivals':
                    $query->whereDate('check_in', Carbon::today());
                    break;
                case 'upcoming_arrivals':
                    $query->where('check_in', '>', Carbon::today())
                          ->where('check_in', '<=', Carbon::today()->addDays(30));
                    break;
                case 'current_guests':
                    $query->where('check_in', '<=', Carbon::today())
                          ->where('check_out', '>', Carbon::today());
                    break;
                case 'past_bookings':
                    $query->where('check_out', '<', Carbon::today());
                    break;
                case 'this_week':
                    $query->whereBetween('check_in', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $query->whereMonth('check_in', Carbon::now()->month)
                          ->whereYear('check_in', Carbon::now()->year);
                    break;
                // 'all' = no additional filter, show all confirmed bookings
            }
            
            Log::info('Applied view filter', ['view_type' => $viewType, 'count_after' => $query->count()]);
        }
        
        // Date filtering - flexible by admin choice
        $dateFilterType = $request->get('date_filter_type', 'booking_date');
        
        if ($request->filled('date_from')) {
            try {
                $dateFrom = Carbon::parse($request->date_from)->startOfDay();
                
                switch ($dateFilterType) {
                    case 'booking_date':
                        $query->where('created_at', '>=', $dateFrom);
                        break;
                    case 'checkin_date':
                        $query->where('check_in', '>=', $dateFrom);
                        break;
                    case 'checkout_date':
                        $query->where('check_out', '>=', $dateFrom);
                        break;
                }
                
                Log::info('Applied date_from filter', [
                    'filter_type' => $dateFilterType,
                    'date_from' => $dateFrom->format('Y-m-d'),
                    'count_after' => $query->count()
                ]);
                
            } catch (\Exception $e) {
                Log::error('Invalid date_from format', ['date_from' => $request->date_from, 'error' => $e->getMessage()]);
            }
        }
        
        if ($request->filled('date_to')) {
            try {
                $dateTo = Carbon::parse($request->date_to)->endOfDay();
                
                switch ($dateFilterType) {
                    case 'booking_date':
                        $query->where('created_at', '<=', $dateTo);
                        break;
                    case 'checkin_date':
                        $query->where('check_in', '<=', $dateTo->format('Y-m-d'));
                        break;
                    case 'checkout_date':
                        $query->where('check_out', '<=', $dateTo->format('Y-m-d'));
                        break;
                }
                
                Log::info('Applied date_to filter', [
                    'filter_type' => $dateFilterType,
                    'date_to' => $dateTo->format('Y-m-d'),
                    'count_after' => $query->count()
                ]);
                
            } catch (\Exception $e) {
                Log::error('Invalid date_to format', ['date_to' => $request->date_to, 'error' => $e->getMessage()]);
            }
        }
        
        // Search filter
        if ($request->filled('search')) {
            $search = trim($request->search);
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
                Log::info('Applied search filter', ['search' => $search, 'count_after' => $query->count()]);
            }
        }
        
        // Final count
        $finalCount = $query->count();
        Log::info('Final booking query result', [
            'final_count' => $finalCount,
            'date_filter_type' => $dateFilterType,
            'view_type' => $request->get('view_type', 'all')
        ]);
        
        // Pagination
        $perPage = $request->get('per_page', 25); // Increase default for business use
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 25;
        
        $bookings = $query->latest('created_at')->paginate($perPage);
        
        // Data for view
        $hotels = Hotel::all();
        $filters = $request->only(['hotel_id', 'view_type', 'date_from', 'date_to', 'search', 'per_page', 'date_filter_type']);
        
        return view('backend.bookings.index', [
            'bookings' => $bookings,
            'hotels' => $hotels,
            'filters' => $filters,
        ]);
    }
    
    public function show($id)
    {
        $booking = Booking::with([
            'hotel', 'roomType', 'package', 'addons', 'payments'
        ])->findOrFail($id);
        
        return view('backend.bookings.show', ['booking' => $booking]);
    }
    
    // Export methods - same logic, simplified status
    public function showExportOptions(Request $request)
    {
        $hotels = Hotel::all();
        $filters = $request->only(['hotel_id', 'view_type', 'date_from', 'date_to', 'search', 'date_filter_type']);
        
        return view('backend.bookings.export_options', compact('hotels', 'filters'));
    }
    
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $query = $this->buildFilterQuery($request);
        $bookings = $query->latest('created_at')->get();
        
        if ($format === 'pdf') {
            return $this->exportPDF($bookings, $request);
        } else {
            return $this->exportCSV($bookings, $request);
        }
    }
    
    private function buildFilterQuery(Request $request)
    {
        // CONSISTENT: Only confirmed bookings
        $query = Booking::with(['hotel', 'roomType', 'package'])
            ->where('payment_status', 'paid');
        
        // Apply same filters as index method
        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }
        
        // Business view filters
        if ($request->filled('view_type')) {
            $viewType = $request->view_type;
            
            switch ($viewType) {
                case 'today_arrivals':
                    $query->whereDate('check_in', Carbon::today());
                    break;
                case 'upcoming_arrivals':
                    $query->where('check_in', '>', Carbon::today())
                          ->where('check_in', '<=', Carbon::today()->addDays(30));
                    break;
                case 'current_guests':
                    $query->where('check_in', '<=', Carbon::today())
                          ->where('check_out', '>', Carbon::today());
                    break;
                case 'past_bookings':
                    $query->where('check_out', '<', Carbon::today());
                    break;
                case 'this_week':
                    $query->whereBetween('check_in', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $query->whereMonth('check_in', Carbon::now()->month)
                          ->whereYear('check_in', Carbon::now()->year);
                    break;
            }
        }
        
        // Date filtering
        $dateFilterType = $request->get('date_filter_type', 'booking_date');
        
        if ($request->filled('date_from')) {
            try {
                $dateFrom = Carbon::parse($request->date_from)->startOfDay();
                
                switch ($dateFilterType) {
                    case 'booking_date':
                        $query->where('created_at', '>=', $dateFrom);
                        break;
                    case 'checkin_date':
                        $query->where('check_in', '>=', $dateFrom);
                        break;
                    case 'checkout_date':
                        $query->where('check_out', '>=', $dateFrom);
                        break;
                }
            } catch (\Exception $e) {
                // Invalid date format, ignore filter
            }
        }
        
        if ($request->filled('date_to')) {
            try {
                $dateTo = Carbon::parse($request->date_to)->endOfDay();
                
                switch ($dateFilterType) {
                    case 'booking_date':
                        $query->where('created_at', '<=', $dateTo);
                        break;
                    case 'checkin_date':
                        $query->where('check_in', '<=', $dateTo->format('Y-m-d'));
                        break;
                    case 'checkout_date':
                        $query->where('check_out', '<=', $dateTo->format('Y-m-d'));
                        break;
                }
            } catch (\Exception $e) {
                // Invalid date format, ignore filter
            }
        }
        
        // Search filter
        if ($request->filled('search')) {
            $search = trim($request->search);
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }
        }
        
        return $query;
    }
    
    private function exportCSV($bookings, $request)
    {
        $viewType = $request->get('view_type', 'all');
        $dateFilterType = $request->get('date_filter_type', 'booking_date');
        $dateRange = '';
        
        if ($request->filled('date_from') || $request->filled('date_to')) {
            $dateRange = '_' . ($request->date_from ?? 'start') . '_to_' . ($request->date_to ?? 'end');
        }
        
        $filename = 'confirmed_bookings_' . $viewType . '_' . $dateFilterType . $dateRange . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($bookings, $viewType, $dateFilterType) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Export info
            fputcsv($file, ['# Confirmed Bookings Export']);
            fputcsv($file, ['# View Type: ' . ucfirst(str_replace('_', ' ', $viewType))]);
            fputcsv($file, ['# Date Filter: ' . ucfirst(str_replace('_', ' ', $dateFilterType))]);
            fputcsv($file, ['# Export Date: ' . Carbon::now()->format('Y-m-d H:i:s')]);
            fputcsv($file, ['# Business Logic: No Cancellations, Payment Confirmed Only']);
            fputcsv($file, []); // Empty row
            
            // Headers
            fputcsv($file, [
                'Booking ID', 'Booking Code', 'Guest Name', 'Email', 'Phone', 'Country',
                'Hotel', 'Room Type', 'Package', 'Check-in Date', 'Check-out Date',
                'Total Nights', 'Adults', 'Children', 'Total Guests', 'Total Amount',
                'Booking Date', 'Days Until Arrival', 'Status'
            ]);
            
            // Data rows
            foreach ($bookings as $booking) {
                $daysUntilArrival = Carbon::parse($booking->check_in)->diffInDays(Carbon::today(), false);
                $statusDesc = 'Confirmed';
                
                if (Carbon::parse($booking->check_in)->isToday()) {
                    $statusDesc = 'Arriving Today';
                } elseif (Carbon::parse($booking->check_in)->isFuture()) {
                    $statusDesc = 'Upcoming';
                } elseif (Carbon::parse($booking->check_out)->isPast()) {
                    $statusDesc = 'Completed';
                } elseif (Carbon::parse($booking->check_in)->isPast() && Carbon::parse($booking->check_out)->isFuture()) {
                    $statusDesc = 'Currently Staying';
                }
                
                fputcsv($file, [
                    $booking->id,
                    $booking->code,
                    $booking->first_name . ' ' . $booking->last_name,
                    $booking->email,
                    $booking->phone,
                    $booking->country ?: 'Not specified',
                    $booking->hotel->name ?? 'Unknown Hotel',
                    $booking->roomType->name ?? 'Unknown Room Type',
                    $booking->package->name ?? 'No Package',
                    $booking->check_in,
                    $booking->check_out,
                    $booking->total_night,
                    $booking->adults,
                    $booking->child,
                    ($booking->adults + $booking->child),
                    number_format($booking->total_amount, 0, ',', '.'),
                    $booking->created_at->format('Y-m-d H:i:s'),
                    $daysUntilArrival,
                    $statusDesc
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function exportPDF($bookings, $request)
    {
        $viewType = $request->get('view_type', 'all');
        $dateFilterType = $request->get('date_filter_type', 'booking_date');
        
        $data = [
            'bookings' => $bookings,
            'filters' => $request->only(['hotel_id', 'view_type', 'date_from', 'date_to', 'search', 'date_filter_type']),
            'export_date' => Carbon::now()->format('d F Y H:i:s'),
            'date_filter_type' => $dateFilterType,
            'view_type' => $viewType,
            'total_bookings' => $bookings->count(),
            'total_revenue' => $bookings->sum('total_amount'),
            'business_note' => 'All bookings shown are confirmed (payment completed). No cancellations or refunds processed.',
        ];
        
        $pdf = Pdf::loadView('backend.bookings.export_pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'confirmed_bookings_' . $viewType . '_report_' . Carbon::now()->format('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }
    
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $booking = Booking::findOrFail($id);
            $bookingCode = $booking->code;
            
            // Only allow deletion of confirmed bookings with proper business reason
            if ($booking->payment_status !== 'paid') {
                throw new \Exception('Only confirmed bookings can be deleted.');
            }
            
            // Delete related records
            DB::table('booking_room_lists')->where('booking_id', $id)->delete();
            DB::table('room_booked_dates')->where('booking_id', $id)->delete();
            
            // Try to delete addons
            try {
                DB::table('booking_addon')->where('booking_id', $id)->delete();
            } catch (\Exception $e) {
                // Table might not exist
            }
            
            // Try to delete payments
            try {
                $paymentTables = [
                    ['table' => 'payments', 'column' => 'booking_id'],
                    ['table' => 'payments', 'column' => 'order_id'], 
                    ['table' => 'booking_payments', 'column' => 'booking_id']
                ];
                
                foreach ($paymentTables as $payment) {
                    try {
                        $deletedPayments = DB::table($payment['table'])->where($payment['column'], $id)->delete();
                        if ($deletedPayments > 0) {
                            break;
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            } catch (\Exception $e) {
                // No payment records
            }
            
            // Delete the booking
            $result = DB::table('bookings')->where('id', $id)->delete();
            
            if ($result > 0) {
                DB::commit();
                
                // Log the business action
                $deletedBy = 'Unknown';
                if (Auth::check()) {
                    $user = Auth::user();
                    $deletedBy = $user->email ?? $user->name ?? ('User ID: ' . $user->id);
                }
                
                Log::warning('Confirmed booking deleted', [
                    'booking_code' => $bookingCode,
                    'deleted_by' => $deletedBy,
                    'deleted_at' => now()->format('Y-m-d H:i:s'),
                    'reason' => 'Admin deletion - no refund processed'
                ]);
                
                return redirect()->route('admin.bookings')
                            ->with('success', "Confirmed booking {$bookingCode} has been deleted. Note: This was a paid booking with no refund processed.");
            } else {
                throw new \Exception('Failed to delete booking record');
            }
                        
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->route('admin.bookings')
                        ->with('error', 'Failed to delete booking: ' . $e->getMessage());
        }
    }
}