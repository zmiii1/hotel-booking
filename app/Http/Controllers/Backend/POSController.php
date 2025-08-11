<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BeachTicket;
use App\Models\TicketOrder;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Carbon\Carbon;

class POSController extends Controller
{
    public function index()
    {
        // Get all active tickets
        $tickets = BeachTicket::active()->get();
        
        // Group tickets by beach and type
        $groupedTickets = [
            'lalassa' => [
                'regular' => $tickets->where('beach_name', 'lalassa')->where('ticket_type', 'regular'),
                'bundling' => $tickets->where('beach_name', 'lalassa')->where('ticket_type', 'bundling')
            ],
            'bodur' => [
                'regular' => $tickets->where('beach_name', 'bodur')->where('ticket_type', 'regular'),
                'bundling' => $tickets->where('beach_name', 'bodur')->where('ticket_type', 'bundling')
            ]
        ];
        
        // Get promo codes yang active dan untuk beach tickets
        $promoCodes = PromoCode::where('is_active', true)
            ->where('applies_to', 'beach_tickets')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->get();
        
        return view('backend.beach-tickets.pos.index', compact('groupedTickets', 'promoCodes'));
    }

    public function applyPromo(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string',
                'subtotal' => 'required|numeric|min:0',
                'ticket_id' => 'required|exists:beach_tickets,id',
                'visit_date' => 'required|date'
            ]);
            
            $searchCode = strtoupper(trim($validated['code']));
            
            // DEBUGGING: Log semua promo codes yang ada
            $allPromoCodes = PromoCode::where('code', $searchCode)->get();
            \Log::info('All promo codes with this code:', $allPromoCodes->toArray());
            
            $promoCode = PromoCode::where('code', $searchCode)
                ->where('is_active', true)
                ->where('applies_to', 'beach_tickets')
                ->first();
                
            \Log::info('Found promo code:', $promoCode ? $promoCode->toArray() : 'Not found');
                
            if (!$promoCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Promo code not found or not applicable to beach tickets'
                ]);
            }
            
            // FIXED: Use visit_date instead of today for validation
            $visitDate = Carbon::parse($validated['visit_date'])->format('Y-m-d');
            $startDate = Carbon::parse($promoCode->start_date)->format('Y-m-d');
            $endDate = Carbon::parse($promoCode->end_date)->format('Y-m-d');
            $today = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            
            \Log::info('CORRECTED Date validation:', [
                'today' => $today,
                'visit_date' => $visitDate,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'visit_date_is_valid' => ($visitDate >= $startDate && $visitDate <= $endDate),
                'promo_period_started' => ($today >= $startDate)
            ]);
            
            // Check if visit date is within promo period
            if ($visitDate < $startDate || $visitDate > $endDate) {
                return response()->json([
                    'success' => false,
                    'message' => "Promo code is not valid for visit date {$visitDate}. Valid period: {$startDate} to {$endDate}"
                ]);
            }
            
            // Optional: Check if promo period has started (for booking restriction)
            // Uncomment if you want to prevent booking before promo starts
            /*
            if ($today < $startDate) {
                return response()->json([
                    'success' => false,
                    'message' => "Promo code booking will be available from {$startDate}. Today is {$today}"
                ]);
            }
            */
            
            // Check usage limit
            if ($promoCode->max_uses && $promoCode->used_count >= $promoCode->max_uses) {
                return response()->json([
                    'success' => false,
                    'message' => 'This promo code has reached its usage limit'
                ]);
            }
            
            // Check minimum purchase
            if ($promoCode->min_purchase && $validated['subtotal'] < $promoCode->min_purchase) {
                return response()->json([
                    'success' => false,
                    'message' => 'Minimum purchase of Rp ' . number_format($promoCode->min_purchase, 0, ',', '.') . ' required'
                ]);
            }
            
            // Check specific ticket restrictions
            $specificTickets = $promoCode->beachTickets()->pluck('beach_tickets.id')->toArray();
            \Log::info('Specific tickets check:', [
                'promo_tickets' => $specificTickets,
                'requested_ticket' => $validated['ticket_id'],
                'has_restrictions' => count($specificTickets) > 0
            ]);
            
            if (count($specificTickets) > 0 && !in_array($validated['ticket_id'], $specificTickets)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This promo code is not applicable to the selected ticket'
                ]);
            }
            
            // Calculate discount dengan max_discount
            $subtotal = $validated['subtotal'];
            $discount = 0;
            
            if ($promoCode->discount_type === 'percentage') {
                $percentageDiscount = ($subtotal * $promoCode->discount_value) / 100;
                
                // Apply max_discount cap jika ada
                if ($promoCode->max_discount && $promoCode->max_discount > 0) {
                    $discount = min($percentageDiscount, $promoCode->max_discount);
                } else {
                    $discount = $percentageDiscount;
                }
            } else {
                $discount = min($promoCode->discount_value, $subtotal);
            }
            
            $discount = min($discount, $subtotal);
            $total = $subtotal - $discount;
            
            \Log::info('Discount calculation:', [
                'subtotal' => $subtotal,
                'discount_type' => $promoCode->discount_type,
                'discount_value' => $promoCode->discount_value,
                'max_discount' => $promoCode->max_discount,
                'calculated_discount' => $discount,
                'final_total' => $total
            ]);
            
            return response()->json([
                'success' => true,
                'discount' => $discount,
                'total' => $total,
                'message' => 'Promo code applied successfully!',
                'promo_code' => [
                    'code' => $promoCode->code,
                    'discount_type' => $promoCode->discount_type,
                    'discount_amount' => $promoCode->discount_value,
                    'max_discount' => $promoCode->max_discount
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Promo code error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    // ... rest of the methods remain the same
    public function processOrder(Request $request)
{
    try {
        // DYNAMIC VALIDATION based on payment method
        $rules = [
            'items' => 'required|array',
            'items.*.id' => 'required|exists:beach_tickets,id',
            'items.*.quantity' => 'required|integer|min:1',
            'customer_name' => 'nullable|string|max:255',
            'visit_date' => 'required|date',
            'additional_notes' => 'nullable|string',
            'payment_method' => 'required|string|in:cash,card',
            'promo_code' => 'nullable|string',
            'applied_promo_code' => 'nullable|string'
        ];
        
        // CONDITIONAL VALIDATION: amount_tendered only required for cash
        if ($request->payment_method === 'cash') {
            $rules['amount_tendered'] = 'required|numeric|min:0';
        } else {
            $rules['amount_tendered'] = 'nullable|numeric|min:0';
        }
        
        $validated = $request->validate($rules);
        
        // Set default customer info for POS orders
        $customerName = $validated['customer_name'] ?: 'Guest';
        $customerEmail = 'pos@system.local';
        $customerPhone = '-';
        
        // Calculate total
        $subtotal = 0;
        $items = [];
        $discount = 0;
        $promoCode = null;
        
        // Process items and calculate subtotal
        foreach ($validated['items'] as $item) {
            $ticket = BeachTicket::findOrFail($item['id']);
            $itemTotal = $ticket->price * $item['quantity'];
            $subtotal += $itemTotal;
            
            $items[] = [
                'ticket' => $ticket,
                'quantity' => $item['quantity'],
                'price' => $ticket->price,
                'total' => $itemTotal
            ];
        }
        
        // Apply promo code logic (sama seperti sebelumnya)
        $promoCodeToUse = $validated['applied_promo_code'] ?? $validated['promo_code'] ?? null;
        
        if (!empty($promoCodeToUse)) {
            $searchCode = strtoupper(trim($promoCodeToUse));
            
            $promoCode = PromoCode::where('code', $searchCode)
                ->where('is_active', true)
                ->where('applies_to', 'beach_tickets')
                ->first();
                
            if ($promoCode) {
                $visitDate = Carbon::parse($validated['visit_date'])->format('Y-m-d');
                $startDate = Carbon::parse($promoCode->start_date)->format('Y-m-d');
                $endDate = Carbon::parse($promoCode->end_date)->format('Y-m-d');
                
                if ($visitDate >= $startDate && $visitDate <= $endDate) {
                    if (!($promoCode->max_uses && $promoCode->used_count >= $promoCode->max_uses)) {
                        if (!($promoCode->min_purchase && $subtotal < $promoCode->min_purchase)) {
                            $specificTickets = $promoCode->beachTickets()->pluck('beach_tickets.id')->toArray();
                            $firstTicketId = $items[0]['ticket']->id;
                            
                            if (count($specificTickets) === 0 || in_array($firstTicketId, $specificTickets)) {
                                if ($promoCode->discount_type === 'percentage') {
                                    $percentageDiscount = ($subtotal * $promoCode->discount_value) / 100;
                                    
                                    if ($promoCode->max_discount && $promoCode->max_discount > 0) {
                                        $discount = min($percentageDiscount, $promoCode->max_discount);
                                    } else {
                                        $discount = $percentageDiscount;
                                    }
                                } else {
                                    $discount = min($promoCode->discount_value, $subtotal);
                                }
                                
                                $discount = min($discount, $subtotal);
                            }
                        }
                    }
                }
            }
        }
        
        // Calculate final total
        $total = $subtotal - $discount;
        
        // PAYMENT METHOD SPECIFIC LOGIC
        if ($validated['payment_method'] === 'cash') {
            // For cash payment, validate amount tendered
            $amountTendered = $validated['amount_tendered'] ?? 0;
            
            if ($amountTendered < $total) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Amount tendered (Rp' . number_format($amountTendered, 0, ',', '.') . ') must be at least the total price (Rp' . number_format($total, 0, ',', '.') . ').');
            }
        } else {
            // For card payment, amount tendered equals total (exact payment)
            $amountTendered = $total;
        }
        
        // Generate order code
        $orderCode = 'TIX-' . strtoupper(substr(uniqid(), -7));
        
        // Create order
        $order = TicketOrder::create([
            'order_code' => $orderCode,
            'beach_ticket_id' => $items[0]['ticket']->id,
            'customer_name' => $customerName,
            'customer_email' => $customerEmail,
            'customer_phone' => $customerPhone,
            'visit_date' => $validated['visit_date'],
            'quantity' => array_sum(array_column($validated['items'], 'quantity')),
            'additional_request' => $validated['additional_notes'] ?? null,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total_price' => $total,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'paid',
            'paid_at' => now(),
            'cashier_id' => Auth::id(),
            'amount_tendered' => $amountTendered,
            'is_offline_order' => true,
            'promo_code_id' => $promoCode ? $promoCode->id : null
        ]);
        
        // Increment promo code usage if applied
        if ($promoCode && $discount > 0) {
            $promoCode->increment('used_count');
        }
        
        // Calculate change (0 for card payment)
        $change = $amountTendered - $total;
        
        \Log::info('Order processed successfully:', [
            'order_code' => $orderCode,
            'payment_method' => $validated['payment_method'],
            'total' => $total,
            'amount_tendered' => $amountTendered,
            'change' => $change
        ]);
        
        // Return receipt view
        return view('backend.beach-tickets.pos.receipt', compact(
            'order', 'items', 'subtotal', 'discount', 'total', 'change', 'validated', 'promoCode'
        ));
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation error:', $e->errors());
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput()
            ->with('error', 'Validation failed: ' . implode(', ', Arr::flatten($e->errors())));
            
    } catch (\Exception $e) {
        \Log::error('Order processing error:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return redirect()->back()
            ->withInput()
            ->with('error', 'An error occurred while processing the order: ' . $e->getMessage());
    }
} 

    public function printReceipt($orderCode)
    {
        $order = TicketOrder::with(['ticket', 'promoCode'])
            ->where('order_code', $orderCode)
            ->firstOrFail();
            
        return view('backend.beach-tickets.pos.print-receipt', compact('order'));
    }
}