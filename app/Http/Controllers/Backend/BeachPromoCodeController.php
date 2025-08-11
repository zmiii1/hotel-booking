<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PromoCode;
use App\Models\BeachTicket;
use Illuminate\Support\Str;

class BeachPromoCodeController extends Controller
{
    public function index()
    {
        $promoCodes = PromoCode::whereIn('applies_to', ['beach_tickets'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('backend.beach-tickets.promo-codes.index', compact('promoCodes'));
    }
    
    public function create()
    {
        $beachTickets = BeachTicket::where('active', 1)->get();
        return view('backend.beach-tickets.promo-codes.create', compact('beachTickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:promo_codes|max:50',
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_uses' => 'nullable|integer|min:1',
            'ticket_ids' => 'nullable|array',
            'ticket_ids.*' => 'exists:beach_tickets,id'
        ]);

        $promoCode = PromoCode::create([
            'code' => Str::upper($request->code),
            'description' => $request->description,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_purchase' => $request->min_purchase,
            'max_discount' => $request->discount_type === 'percentage' ? $request->max_discount : null,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'max_uses' => $request->max_uses,
            'used_count' => 0,
            'is_active' => $request->has('is_active'),
            'applies_to' => 'beach_tickets' // ATAU bisa 'all' jika mau general
        ]);

        if ($request->ticket_ids) {
            $promoCode->beachTickets()->attach($request->ticket_ids);
        }

        return redirect()->route('backend.beach-tickets.promo-codes.index')
            ->with('success', 'Beach ticket promo code created successfully');
    }

    public function edit($id)
    {
        // FIXED: Cari promo code yang berlaku untuk beach tickets ATAU all
        $promoCode = PromoCode::whereIn('applies_to', ['beach_tickets'])
            ->with('beachTickets')
            ->findOrFail($id);
        
        $beachTickets = BeachTicket::where('active', 1)->get();
        return view('backend.beach-tickets.promo-codes.edit', compact('promoCode', 'beachTickets'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|max:50|unique:promo_codes,code,'.$id,
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_uses' => 'nullable|integer|min:1',
            'ticket_ids' => 'nullable|array',
            'ticket_ids.*' => 'exists:beach_tickets,id'
        ]);

        // FIXED: Cari promo code yang berlaku untuk beach tickets ATAU all
        $promoCode = PromoCode::whereIn('applies_to', ['beach_tickets'])->findOrFail($id);
        
        $promoCode->update([
            'code' => Str::upper($request->code),
            'description' => $request->description,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_purchase' => $request->min_purchase,
            'max_discount' => $request->discount_type === 'percentage' ? $request->max_discount : null,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'max_uses' => $request->max_uses,
            'is_active' => $request->has('is_active'),
            // JANGAN ubah applies_to di sini untuk menjaga konsistensi
        ]);

        $promoCode->beachTickets()->sync($request->ticket_ids ?? []);

        return redirect()->route('backend.beach-tickets.promo-codes.index')
            ->with('success', 'Beach ticket promo code updated successfully');
    }

    public function destroy($id)
    {
        // FIXED: Cari promo code yang berlaku untuk beach tickets ATAU all
        $promoCode = PromoCode::whereIn('applies_to', ['beach_tickets'])->findOrFail($id);
        
        $promoCode->beachTickets()->detach();
        $promoCode->delete();

        return redirect()->route('backend.beach-tickets.promo-codes.index')
            ->with('success', 'Beach ticket promo code deleted successfully');
    }
}