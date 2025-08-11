<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Models\BeachTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BeachPromoCodeController extends Controller
{
    public function applyPromo(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|string|max:50'
        ]);
        
        $code = $request->promo_code;
        $promoCode = PromoCode::where('code', $code)->first();
        
        // Get checkout data from session
        $checkoutData = Session::get('checkout_data');
        $ticketId = $checkoutData['ticket_id'] ?? null;
        $totalPrice = $checkoutData['total_price'] ?? 0;
        $visitDate = $checkoutData['visit_date'] ?? null;
        
        if (!$promoCode) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid promo code.'
            ]);
        }
        
        // Validate for beach ticket
        $validation = $promoCode->isValidForBeachTicket($ticketId, $totalPrice, $visitDate);
        
        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $validation['message']
            ]);
        }
        
        // Calculate discount
        $discount = $validation['discount'];
        
        // Store promo code in session
        Session::put('beach_promo_code', [
            'code' => $promoCode->code,
            'discount' => $discount,
            'type' => $promoCode->discount_type,
            'value' => $promoCode->discount_value
        ]);
        
        // Calculate new total
        $newTotal = $totalPrice - $discount;
        
        return response()->json([
            'success' => true,
            'message' => $validation['message'],
            'discount' => $discount,
            'new_total' => $newTotal,
            'formatted_discount' => 'Rp ' . number_format($discount, 0, ',', '.'),
            'formatted_new_total' => 'Rp ' . number_format($newTotal, 0, ',', '.')
        ]);
    }
    
    public function removePromo()
    {
        Session::forget('beach_promo_code');
        
        // Recalculate total
        $checkoutData = Session::get('checkout_data');
        $ticketId = $checkoutData['ticket_id'] ?? null;
        $quantity = $checkoutData['quantity'] ?? 1;
        
        if (!$ticketId) {
            return response()->json([
                'success' => false,
                'message' => 'No ticket found in session.'
            ]);
        }
        
        $ticket = BeachTicket::findOrFail($ticketId);
        $totalPrice = $ticket->price * $quantity;
        
        // Update checkout data
        $checkoutData['total_price'] = $totalPrice;
        Session::put('checkout_data', $checkoutData);
        
        return response()->json([
            'success' => true,
            'message' => 'Promo code removed.',
            'new_total' => $totalPrice,
            'formatted_new_total' => 'Rp ' . number_format($totalPrice, 0, ',', '.')
        ]);
    }
}