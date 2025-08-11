<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RoomAddOns;
use App\Models\RoomPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomAddOnsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addons = RoomAddOns::orderBy('category')->orderBy('name')->get();
        
        return view('backend.addons.index', compact('addons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = RoomAddOns::CATEGORIES;
        $guestTypes = RoomAddOns::GUEST_TYPES;
        $priceTypes = RoomAddOns::PRICE_TYPES;
        $packages = RoomPackage::where('status', true)->get();
        
        return view('backend.addons.create', compact('categories', 'guestTypes', 'priceTypes', 'packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'normal_price' => 'nullable|numeric|min:0',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_prepayment_required' => 'boolean',
            'for_guests_type' => 'required|string',
            'guest_count' => 'nullable|integer|min:1',
            'is_included' => 'boolean',
            'status' => 'boolean',
            'price_type' => 'required|string',
            'is_bestseller' => 'boolean',
            'is_sale' => 'boolean',
            'packages' => 'nullable|array',
            'packages.*' => 'exists:room_packages,id'
        ]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'addon_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/addons'), $filename);
            $validated['image'] = 'upload/addons/' . $filename;
        }
        
        // Convert checkbox fields
        $validated['is_prepayment_required'] = $request->has('is_prepayment_required');
        $validated['is_included'] = $request->has('is_included');
        $validated['status'] = $request->has('status');
        $validated['is_bestseller'] = $request->has('is_bestseller');
        $validated['is_sale'] = $request->has('is_sale');
        
        // Create addon
        $addon = RoomAddOns::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'normal_price' => $validated['normal_price'],
            'category' => $validated['category'],
            'image' => $validated['image'] ?? null,
            'is_prepayment_required' => $validated['is_prepayment_required'],
            'for_guests_type' => $validated['for_guests_type'],
            'guest_count' => $validated['guest_count'],
            'is_included' => $validated['is_included'],
            'status' => $validated['status'],
            'price_type' => $validated['price_type'],
            'is_bestseller' => $validated['is_bestseller'],
            'is_sale' => $validated['is_sale'],
        ]);
        
        // Sync with packages if selected
        if (isset($validated['packages'])) {
            $addon->packages()->sync($validated['packages']);
        }
        
        return redirect()->route('room-addons.index')
            ->with('success', 'Room add-on created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomAddOns $roomAddon)
    {
        return view('backend.addons.show', compact('roomAddon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoomAddOns $roomAddon)
    {
        $categories = RoomAddOns::CATEGORIES;
        $guestTypes = RoomAddOns::GUEST_TYPES;
        $priceTypes = RoomAddOns::PRICE_TYPES;
        $packages = RoomPackage::where('status', true)->get();
        
        // Get the IDs of packages that include this addon
        $selectedPackages = $roomAddon->packages->pluck('id')->toArray();
        
        return view('backend.addons.edit', compact('roomAddon', 'categories', 'guestTypes', 'priceTypes', 'packages', 'selectedPackages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoomAddOns $roomAddon)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'normal_price' => 'nullable|numeric|min:0',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'for_guests_type' => 'required|string',
            'guest_count' => 'nullable|integer|min:1',
            'price_type' => 'required|string',
            'packages' => 'nullable|array',
            'packages.*' => 'exists:room_packages,id'
        ]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($roomAddon->image && file_exists(public_path($roomAddon->image))) {
                unlink(public_path($roomAddon->image));
            }
            
            // Upload new image
            $image = $request->file('image');
            $filename = 'addon_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/addons'), $filename);
            $validated['image'] = 'upload/addons/' . $filename;
        }
        
        // Convert checkbox fields
        $validated['is_prepayment_required'] = $request->has('is_prepayment_required');
        $validated['is_included'] = $request->has('is_included');
        $validated['status'] = $request->has('status');
        $validated['is_bestseller'] = $request->has('is_bestseller');
        $validated['is_sale'] = $request->has('is_sale');
        
        // Update addon
        $roomAddon->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'normal_price' => $validated['normal_price'],
            'category' => $validated['category'],
            'image' => $validated['image'] ?? $roomAddon->image,
            'is_prepayment_required' => $validated['is_prepayment_required'],
            'for_guests_type' => $validated['for_guests_type'],
            'guest_count' => $validated['guest_count'],
            'is_included' => $validated['is_included'],
            'status' => $validated['status'],
            'price_type' => $validated['price_type'],
            'is_bestseller' => $validated['is_bestseller'],
            'is_sale' => $validated['is_sale'],
        ]);
        
        // Sync with packages if selected
        if (isset($validated['packages'])) {
            $roomAddon->packages()->sync($validated['packages']);
        } else {
            $roomAddon->packages()->detach();
        }
        
        return redirect()->route('room-addons.index')
            ->with('success', 'Room add-on updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomAddOns $roomAddon)
    {
        // Delete image if exists
        if ($roomAddon->image && file_exists(public_path($roomAddon->image))) {
            unlink(public_path($roomAddon->image));
        }
        
        // Detach from packages
        $roomAddon->packages()->detach();
        
        // Delete addon
        $roomAddon->delete();
        
        return redirect()->route('room-addons.index')
            ->with('success', 'Room add-on deleted successfully');
    }
}