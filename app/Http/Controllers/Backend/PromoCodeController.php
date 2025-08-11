<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PromoCode;
use App\Models\Room;
use Illuminate\Support\Str;

class PromoCodeController extends Controller
{
    public function index()
    {
        // FILTER: Hanya ambil promo code untuk rooms
        $promoCodes = PromoCode::where('applies_to', 'rooms')
            ->latest()
            ->paginate(10);
        
        return view('backend.promo_code.index', compact('promoCodes'));
    }

    public function create()
    {
        $rooms = Room::all();
        return view('backend.promo_code.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:promo_codes|max:50',
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_uses' => 'nullable|integer|min:1',
            'room_ids' => 'nullable|array',
            'room_ids.*' => 'exists:rooms,id'
        ]);

        $promoCode = PromoCode::create([
            'code' => Str::upper($request->code),
            'description' => $request->description,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'max_uses' => $request->max_uses,
            'is_active' => $request->has('is_active'),
            'applies_to' => 'rooms' // HARDCODE untuk rooms
        ]);

        if ($request->room_ids) {
            $promoCode->rooms()->attach($request->room_ids);
        }

        return redirect()->route('promo.codes')->with('success', 'Room promo code created successfully');
    }

    public function edit($id)
    {
        // FILTER: Hanya edit promo code untuk rooms
        $promoCode = PromoCode::where('applies_to', 'rooms')
            ->with('rooms')
            ->findOrFail($id);
        
        $rooms = Room::all();
        return view('backend.promo_code.edit', compact('promoCode', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|max:50|unique:promo_codes,code,'.$id,
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_uses' => 'nullable|integer|min:1',
            'room_ids' => 'nullable|array',
            'room_ids.*' => 'exists:rooms,id'
        ]);

        // FILTER: Hanya update promo code untuk rooms
        $promoCode = PromoCode::where('applies_to', 'rooms')->findOrFail($id);
        
        $promoCode->update([
            'code' => Str::upper($request->code),
            'description' => $request->description,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'max_uses' => $request->max_uses,
            'is_active' => $request->has('is_active'),
            'applies_to' => 'rooms' // PASTIKAN tetap rooms
        ]);

        $promoCode->rooms()->sync($request->room_ids ?? []);

        return redirect()->route('promo.codes')->with('success', 'Room promo code updated successfully');
    }

    public function destroy($id)
    {
        // FILTER: Hanya hapus promo code untuk rooms
        $promoCode = PromoCode::where('applies_to', 'rooms')->findOrFail($id);
        
        $promoCode->rooms()->detach();
        $promoCode->delete();

        return redirect()->route('promo.codes')->with('success', 'Room promo code deleted successfully');
    }
}