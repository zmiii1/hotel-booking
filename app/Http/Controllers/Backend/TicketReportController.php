<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TicketOrder;
use App\Models\BeachTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class TicketReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        // Parse dates
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        
        try {
            // Sales by day
            $salesByDay = TicketOrder::where('payment_status', 'paid')
                ->whereBetween('ticket_orders.created_at', [$start, $end])
                ->select(
                    DB::raw('DATE(ticket_orders.created_at) as date'),
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(total_price) as total')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            // Sales by ticket
            $salesByTicket = TicketOrder::where('payment_status', 'paid')
                ->whereBetween('ticket_orders.created_at', [$start, $end])
                ->select(
                    'beach_ticket_id',
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(total_price) as total')
                )
                ->with('ticket')
                ->groupBy('beach_ticket_id')
                ->orderBy('total', 'desc')
                ->get();
            
            // Sales by beach
            $salesByBeach = TicketOrder::where('payment_status', 'paid')
                ->whereBetween('ticket_orders.created_at', [$start, $end])
                ->join('beach_tickets', 'ticket_orders.beach_ticket_id', '=', 'beach_tickets.id')
                ->select(
                    'beach_tickets.beach_name',
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(ticket_orders.total_price) as total')
                )
                ->groupBy('beach_tickets.beach_name')
                ->get();
            
            // Online vs Offline sales
            // Check if is_offline_order column exists
            if (Schema::hasColumn('ticket_orders', 'is_offline_order')) {
                $salesByChannel = TicketOrder::where('payment_status', 'paid')
                    ->whereBetween('ticket_orders.created_at', [$start, $end])
                    ->select(
                        DB::raw('is_offline_order'),
                        DB::raw('COUNT(*) as count'),
                        DB::raw('SUM(total_price) as total')
                    )
                    ->groupBy('is_offline_order')
                    ->get()
                    ->map(function($item) {
                        return [
                            'channel' => $item->is_offline_order ? 'Offline' : 'Online',
                            'count' => $item->count,
                            'total' => $item->total
                        ];
                    });
            } else {
                // Fallback if column doesn't exist
                $salesByChannel = collect([
                    [
                        'channel' => 'All Orders',
                        'count' => TicketOrder::where('payment_status', 'paid')
                            ->whereBetween('ticket_orders.created_at', [$start, $end])
                            ->count(),
                        'total' => TicketOrder::where('payment_status', 'paid')
                            ->whereBetween('ticket_orders.created_at', [$start, $end])
                            ->sum('total_price')
                    ]
                ]);
            }
            
            // Summary statistics
            $summary = [
                'total_sales' => TicketOrder::where('payment_status', 'paid')
                    ->whereBetween('ticket_orders.created_at', [$start, $end])
                    ->sum('total_price'),
                'total_orders' => TicketOrder::where('payment_status', 'paid')
                    ->whereBetween('ticket_orders.created_at', [$start, $end])
                    ->count(),
                'average_order_value' => TicketOrder::where('payment_status', 'paid')
                    ->whereBetween('ticket_orders.created_at', [$start, $end])
                    ->avg('total_price') ?? 0,
                'total_visitors' => TicketOrder::where('payment_status', 'paid')
                    ->whereBetween('ticket_orders.created_at', [$start, $end])
                    ->sum('quantity')
            ];
            
            return view('backend.beach-tickets.reports.index', compact(
                'startDate', 'endDate', 'salesByDay', 'salesByTicket', 
                'salesByBeach', 'salesByChannel', 'summary'
            ));
            
        } catch (\Exception $e) {
            // Log the error
            Log::error('Beach ticket report error: ' . $e->getMessage());
            
            // Return a view with error message
            return view('backend.beach-tickets.reports.error', [
                'message' => 'An error occurred while generating the report: ' . $e->getMessage(),
                'startDate' => $startDate,
                'endDate' => $endDate
            ]);
        }
    }

    
    /**
     * Export report to Excel/CSV
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        // Parse dates
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        
        // Get detailed order data
        $orders = TicketOrder::with(['ticket', 'customer'])
            ->where('payment_status', 'paid')
            ->whereBetween('ticket_orders.created_at', [$start, $end]) // Add table name
            ->orderBy('ticket_orders.created_at') // Add table name
            ->get();

        // Export logic (simplified for brevity)
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="beach-ticket-report-' . $startDate . '-to-' . $endDate . '.csv"',
        ];
        
        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'Order Code', 'Customer Name', 'Ticket', 'Beach', 'Visit Date',
                'Quantity', 'Total Price', 'Payment Method', 'Order Date',
                'Channel', 'Cashier'
            ]);
            
            // Add data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_code,
                    $order->customer_name,
                    $order->ticket->name,
                    ucfirst($order->ticket->beach_name),
                    $order->visit_date,
                    $order->quantity,
                    $order->total_price,
                    ucfirst($order->payment_method),
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->is_offline_order ? 'Offline' : 'Online',
                    $order->cashier ? $order->cashier->name : 'System'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}