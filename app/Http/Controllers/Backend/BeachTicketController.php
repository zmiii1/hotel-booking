<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BeachTicket;
use App\Models\TicketBenefit;
use App\Models\TicketOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Response;
use Barryvdh\DomPDF\Facade\Pdf;

class BeachTicketController extends Controller
{
    // ==========================================
    // DASHBOARD METHODS
    // ==========================================
    
    public function dashboard()
    {
        // Get date ranges
        $today = Carbon::today();
        $thisWeek = Carbon::today()->startOfWeek();
        $thisMonth = Carbon::today()->startOfMonth();
        
        // Total stats (all time)
        $totalOrders = TicketOrder::where('payment_status', 'paid')->count();
        $totalRevenue = TicketOrder::where('payment_status', 'paid')->sum('total_price');
        
        // Calculate average order value
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        // Today's stats
        $todayOrders = TicketOrder::where('payment_status', 'paid')
                        ->whereDate('created_at', $today)
                        ->count();
        $todayRevenue = TicketOrder::where('payment_status', 'paid')
                        ->whereDate('created_at', $today)
                        ->sum('total_price');
        
        // Weekly stats
        $weeklyOrders = TicketOrder::where('payment_status', 'paid')
                        ->whereDate('created_at', '>=', $thisWeek)
                        ->count();
        $weeklyRevenue = TicketOrder::where('payment_status', 'paid')
                        ->whereDate('created_at', '>=', $thisWeek)
                        ->sum('total_price');
        
        // Monthly stats
        $monthlyOrders = TicketOrder::where('payment_status', 'paid')
                        ->whereDate('created_at', '>=', $thisMonth)
                        ->count();
        $monthlyRevenue = TicketOrder::where('payment_status', 'paid')
                        ->whereDate('created_at', '>=', $thisMonth)
                        ->sum('total_price');
        
        // Channel breakdown (Website vs POS)
        $websiteOrders = TicketOrder::where('payment_status', 'paid')
                        ->whereNull('cashier_id')
                        ->count();
        
        $websiteRevenue = TicketOrder::where('payment_status', 'paid')
                         ->whereNull('cashier_id')
                         ->sum('total_price');
        
        $posOrders = TicketOrder::where('payment_status', 'paid')
                    ->whereNotNull('cashier_id')
                    ->count();
        
        $posRevenue = TicketOrder::where('payment_status', 'paid')
                     ->whereNotNull('cashier_id')
                     ->sum('total_price');
        
        // Get recent orders with cashier information
        $recentOrders = TicketOrder::with(['ticket', 'cashier'])
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();
        
        // Top-selling tickets with total sold quantity
        $topTickets = TicketOrder::where('payment_status', 'paid')
                    ->select(
                        'beach_ticket_id', 
                        DB::raw('count(*) as order_count'),
                        DB::raw('sum(quantity) as total_sold'),
                        DB::raw('sum(total_price) as revenue')
                    )
                    ->with('ticket')
                    ->groupBy('beach_ticket_id')
                    ->orderBy('total_sold', 'desc')
                    ->limit(5)
                    ->get();
        
        // Performance metrics for charts
        $dailySalesData = $this->getDailySalesData();
        $ticketTypeData = $this->getTicketTypeData();
        $beachSalesData = $this->getBeachSalesData();
        
        return view('backend.beach-tickets.dashboard', compact(
            'totalOrders', 'totalRevenue', 'averageOrderValue',
            'todayOrders', 'todayRevenue', 'weeklyOrders', 'weeklyRevenue',
            'monthlyOrders', 'monthlyRevenue', 'websiteOrders', 'websiteRevenue',
            'posOrders', 'posRevenue', 'recentOrders', 'topTickets',
            'dailySalesData', 'ticketTypeData', 'beachSalesData'
        ));
    }

    // ==========================================
    // TICKET MANAGEMENT METHODS (CRUD)
    // ==========================================
    
    public function index()
    {
        $tickets = BeachTicket::with('benefits')->orderBy('beach_name')->orderBy('ticket_type')->get();
        return view('backend.beach-tickets.manage.index', compact('tickets'));
    }
    
    public function create()
    {
        return view('backend.beach-tickets.manage.create');
    }
    
    public function store(Request $request)
{
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'beach_name' => 'required|string|in:lalassa,bodur',
        'ticket_type' => 'required|string|in:regular,bundling',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'image_url' => 'nullable|image_url_url|max:2048',
        'benefits' => 'nullable|array',
        'benefits.*.benefit_name' => 'nullable|string|max:255'
    ]);
    
    // Handle image upload
    $imageUrl = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('beach-tickets', 'public');
        $imageUrl = $imagePath;
        \Log::info('Image uploaded:', ['path' => $imagePath]);
    }
    
    // Prepare data untuk create
    $ticketData = [
        'name' => $validated['name'],
        'beach_name' => $validated['beach_name'],
        'ticket_type' => $validated['ticket_type'],
        'price' => $validated['price'],
        'description' => $validated['description'] ?? null,
        'image_url' => $imageUrl, // Gunakan image_url sesuai dengan fillable
        'active' => $request->has('active') // true jika checkbox dicentang
    ];
    
    \Log::info('Creating ticket with data:', $ticketData);
    
    // Create ticket
    try {
        $ticket = BeachTicket::create($ticketData);
        \Log::info('Ticket created successfully:', ['id' => $ticket->id]);
        
        // Create benefits
        if (isset($validated['benefits'])) {
            foreach ($validated['benefits'] as $benefit) {
                if (!empty($benefit['benefit_name'])) {
                    TicketBenefit::create([
                        'beach_ticket_id' => $ticket->id,
                        'benefit_name' => $benefit['benefit_name']
                    ]);
                    \Log::info('Benefit created:', ['benefit' => $benefit['benefit_name']]);
                }
            }
        }
        
        return redirect()->route('backend.beach-tickets.manage.index')
            ->with('success', 'Beach ticket created successfully.');
            
    } catch (\Exception $e) {
        \Log::error('Error creating ticket:', [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'data' => $ticketData
        ]);
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Error creating ticket: ' . $e->getMessage());
    }
}
    
    public function edit($id)
    {
        $ticket = BeachTicket::with('benefits')->findOrFail($id);
        return view('backend.beach-tickets.manage.edit', compact('ticket'));
    }
    
    public function update(Request $request, $id)
{
    $ticket = BeachTicket::findOrFail($id);
    
    // Debug log
    \Log::info('=== UPDATE TICKET ===', [
        'ticket_id' => $id,
        'current_image_url' => $ticket->image_url,
        'has_new_image' => $request->hasFile('image'),
        'request_data' => $request->except(['_token', '_method', 'image'])
    ]);
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'beach_name' => 'required|string|in:lalassa,bodur',
        'ticket_type' => 'required|string|in:regular,bundling',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif',
        'benefits' => 'nullable|array',
        'benefits.*.id' => 'nullable|exists:ticket_benefits,id',
        'benefits.*.benefit_name' => 'nullable|string|max:255'
    ]);
    
    // Handle image upload
    $imageUrl = $ticket->getAttributes()['image_url']; // Keep existing image by default
    
    if ($request->hasFile('image')) {
        \Log::info('Processing new image upload...');
        
        // Delete old image if it exists and is not a default image
        $oldImageUrl = $ticket->getAttributes()['image_url'];
        if ($oldImageUrl && !str_contains($oldImageUrl, 'frontend/assets/img/')) {
            $oldImagePath = $oldImageUrl;
            if (Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
                \Log::info('Deleted old image:', ['path' => $oldImagePath]);
            }
        }
        
        // Store new image
        $imagePath = $request->file('image')->store('beach-tickets', 'public');
        $imageUrl = $imagePath;
        
        \Log::info('New image uploaded:', [
            'path' => $imagePath,
            'full_url' => asset('storage/' . $imagePath)
        ]);
    }
    
    // Prepare update data
    $updateData = [
        'name' => $validated['name'],
        'beach_name' => $validated['beach_name'],
        'ticket_type' => $validated['ticket_type'],
        'price' => $validated['price'],
        'description' => $validated['description'] ?? null,
        'image_url' => $imageUrl, // Use image_url field
        'active' => $request->has('active')
    ];
    
    \Log::info('Updating ticket with data:', $updateData);
    
    // Update ticket
    $ticket->update($updateData);
    
    \Log::info('Ticket updated successfully:', [
        'new_image_url' => $ticket->fresh()->image_url
    ]);
    
    // Update benefits
    if (isset($validated['benefits'])) {
        $existingIds = $ticket->benefits->pluck('id')->toArray();
        $updatedIds = [];
        
        foreach ($validated['benefits'] as $benefit) {
            if (!empty($benefit['benefit_name']) && trim($benefit['benefit_name']) !== '') {
                if (isset($benefit['id'])) {
                    // Update existing benefit
                    $ticketBenefit = TicketBenefit::find($benefit['id']);
                    if ($ticketBenefit) {
                        $ticketBenefit->benefit_name = trim($benefit['benefit_name']);
                        $ticketBenefit->save();
                        $updatedIds[] = $benefit['id'];
                    }
                } else {
                    // Create new benefit
                    $ticketBenefit = TicketBenefit::create([
                        'beach_ticket_id' => $ticket->id,
                        'benefit_name' => trim($benefit['benefit_name'])
                    ]);
                    $updatedIds[] = $ticketBenefit->id;
                }
            }
        }
        
        // Delete removed benefits
        $toDelete = array_diff($existingIds, $updatedIds);
        if (!empty($toDelete)) {
            TicketBenefit::whereIn('id', $toDelete)->delete();
        }
    } else {
        // Delete all benefits if none provided
        $ticket->benefits()->delete();
    }
    
    return redirect()->route('backend.beach-tickets.manage.index')
        ->with('success', 'Beach ticket updated successfully.');
}
    
    public function destroy($id)
    {
        $ticket = BeachTicket::findOrFail($id);
        
        // Delete image
        if ($ticket->image_url) {
            Storage::disk('public')->delete($ticket->image_url);
        }
        
        // Delete benefits
        $ticket->benefits()->delete();
        
        // Delete ticket
        $ticket->delete();
        
        return redirect()->route('backend.beach-tickets.manage.index')
            ->with('success', 'Beach ticket deleted successfully.');
    }

    // ==========================================
    // EXPORT & REPORT METHODS
    // ==========================================
    
    public function showExportForm(Request $request)
    {
        $filters = $request->only(['payment_method', 'order_type', 'date_from', 'date_to', 'search']);
        return view('backend.beach-tickets.orders.export', compact('filters'));
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $filters = $this->getFilters($request);
        
        $query = TicketOrder::with(['cashier', 'ticket'])->orderBy('created_at', 'desc');
        $query = $this->applyFilters($query, $filters);
        $orders = $query->get();
        
        if ($format === 'pdf') {
            return $this->exportPDF($orders, $filters);
        } else {
            return $this->exportCSV($orders, $filters);
        }
    }

    public function getQuickStats(Request $request)
    {
        $filters = $this->getFilters($request);
        $query = TicketOrder::query();
        $query = $this->applyFilters($query, $filters);
        $orders = $query->get();
        
        return response()->json([
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total_price'),
            'total_tickets' => $orders->sum('quantity'),
            'total_discount' => $orders->sum('discount'),
            'cash_orders' => $orders->where('payment_method', 'cash')->count(),
            'card_orders' => $orders->where('payment_method', 'card')->count(),
            'online_orders' => $orders->where('payment_method', 'xendit')->count(),
            'pos_orders' => $orders->where('is_offline_order', true)->count(),
            'web_orders' => $orders->where('is_offline_order', false)->count(),
            'average_order_value' => $orders->avg('total_price'),
            'average_tickets_per_order' => $orders->avg('quantity'),
        ]);
    }
    public function previewData(Request $request)
    {
        $filters = $this->getFilters($request);
        
        $query = TicketOrder::with(['cashier', 'ticket'])->orderBy('created_at', 'desc');
        $query = $this->applyFilters($query, $filters);
        
        // Limit to 10 records for preview
        $orders = $query->limit(10)->get();
        $totalCount = $query->count();
        
        return response()->json([
            'success' => true,
            'orders' => $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_code' => $order->order_code,
                    'customer_name' => $order->customer_name,
                    'customer_email' => $order->customer_email ?? '',
                    'customer_phone' => $order->customer_phone ?? '',
                    'visit_date' => $order->visit_date,
                    'visit_day' => Carbon::parse($order->visit_date)->format('l'),
                    'quantity' => $order->quantity,
                    'price_per_ticket' => $order->price_per_ticket ?? ($order->total_price / $order->quantity),
                    'total_price' => $order->total_price,
                    'discount' => $order->discount ?? 0,
                    'final_amount' => $order->total_price - ($order->discount ?? 0),
                    'payment_method' => ucfirst($order->payment_method),
                    'order_type' => $order->is_offline_order ? 'Offline (POS)' : 'Online',
                    'cashier_name' => $order->cashier ? $order->cashier->name : 'System',
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    'formatted_visit_date' => Carbon::parse($order->visit_date)->format('d/m/Y'),
                    'formatted_total_price' => 'Rp' . number_format($order->total_price, 0, ',', '.'),
                    'formatted_discount' => $order->discount ? 'Rp' . number_format($order->discount, 0, ',', '.') : '-',
                    'formatted_final_amount' => 'Rp' . number_format($order->total_price - ($order->discount ?? 0), 0, ',', '.'),
                ];
            }),
            'total_count' => $totalCount,
            'showing_count' => $orders->count(),
            'filters' => $filters
        ]);
    }

    // ==========================================
    // PRIVATE HELPER METHODS
    // ==========================================

    private function getDailySalesData()
    {
        $dates = [];
        $sales = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dates[] = $date->format('M d');
            
            $dailySale = TicketOrder::where('payment_status', 'paid')
                        ->whereDate('created_at', $date)
                        ->sum('total_price');
            $sales[] = $dailySale;
        }
        
        return [
            'labels' => $dates,
            'data' => $sales
        ];
    }
    
    private function getTicketTypeData()
    {
        $ticketTypes = TicketOrder::where('payment_status', 'paid')
                      ->join('beach_tickets', 'ticket_orders.beach_ticket_id', '=', 'beach_tickets.id')
                      ->select(
                          'beach_tickets.ticket_type',
                          DB::raw('sum(ticket_orders.quantity) as total_sold')
                      )
                      ->groupBy('beach_tickets.ticket_type')
                      ->get();
        
        return [
            'labels' => $ticketTypes->pluck('ticket_type')->map(function($type) {
                return ucfirst($type);
            })->toArray(),
            'data' => $ticketTypes->pluck('total_sold')->toArray()
        ];
    }
    
    private function getBeachSalesData()
    {
        $beachSales = TicketOrder::where('payment_status', 'paid')
                     ->join('beach_tickets', 'ticket_orders.beach_ticket_id', '=', 'beach_tickets.id')
                     ->select(
                         'beach_tickets.beach_name',
                         DB::raw('sum(ticket_orders.total_price) as total_revenue')
                     )
                     ->groupBy('beach_tickets.beach_name')
                     ->get();
        
        return [
            'labels' => $beachSales->pluck('beach_name')->map(function($name) {
                return ucfirst($name);
            })->toArray(),
            'data' => $beachSales->pluck('total_revenue')->toArray()
        ];
    }

    private function exportCSV($orders, $filters)
    {
        $filename = 'beach_ticket_orders_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // BOM for UTF-8
            
            fputcsv($file, [
                'Order ID', 'Order Code', 'Customer Name', 'Customer Email',
                'Customer Phone', 'Visit Date', 'Visit Day', 'Quantity',
                'Price Per Ticket', 'Subtotal', 'Discount', 'Total Amount',
                'Payment Method', 'Order Type', 'Is Offline Order', 'Cashier Name',
                'Order Date', 'Order Time', 'Created At', 'Updated At'
            ]);

            foreach ($orders as $order) {
                $visitDate = Carbon::parse($order->visit_date);
                $pricePerTicket = $order->price_per_ticket ?? ($order->total_price / $order->quantity);
                
                fputcsv($file, [
                    $order->id, $order->order_code, $order->customer_name,
                    $order->customer_email ?? '', $order->customer_phone ?? '',
                    $visitDate->format('Y-m-d'), $visitDate->format('l'),
                    $order->quantity, $pricePerTicket, $order->total_price,
                    $order->discount ?? 0, $order->total_price - ($order->discount ?? 0),
                    ucfirst($order->payment_method),
                    $order->is_offline_order ? 'Offline (POS)' : 'Online',
                    $order->is_offline_order ? 'Yes' : 'No',
                    $order->cashier ? $order->cashier->name : 'System',
                    $order->created_at->format('Y-m-d'), $order->created_at->format('H:i:s'),
                    $order->created_at->format('Y-m-d H:i:s'), $order->updated_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function exportPDF($orders, $filters)
    {
        $data = [
            'orders' => $orders,
            'filters' => $filters,
            'export_date' => Carbon::now()->format('d M Y H:i:s'),
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total_price'),
            'total_tickets' => $orders->sum('quantity'),
            'total_discount' => $orders->sum('discount'),
        ];

        $pdf = PDF::loadView('backend.beach-tickets.orders.report-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'beach_ticket_orders_report_' . date('Y-m-d_H-i-s') . '.pdf';
        return $pdf->download($filename);
    }

    private function getFilters(Request $request)
    {
        return [
            'payment_method' => $request->get('payment_method'),
            'order_type' => $request->get('order_type'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'search' => $request->get('search'),
        ];
    }

    private function applyFilters($query, $filters)
    {
        if (!empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (!empty($filters['order_type'])) {
            if ($filters['order_type'] === 'offline') {
                $query->where('is_offline_order', true);
            } elseif ($filters['order_type'] === 'online') {
                $query->where('is_offline_order', false);
            }
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('visit_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('visit_date', '<=', $filters['date_to']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        return $query;
    }
}