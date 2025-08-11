<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomPackage;
use Illuminate\Support\Str;

class RoomPackageController extends Controller
{
    public function index()
    {
        $packages = RoomPackage::latest()->get();
        return view('backend.room_package.index', compact('packages'));
    }
    
    public function create()
    {
        return view('backend.room_package.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:room_packages,code',
            'description' => 'nullable|string',
            'price_adjustment' => 'required|numeric',
            'inclusions' => 'nullable|array',
            'amenities' => 'nullable|array',
        ]);
        
        RoomPackage::create([
            'name' => $request->name,
            'code' => Str::upper($request->code),
            'description' => $request->description,
            'inclusions' => json_encode($request->inclusions),
            'amenities' => json_encode($request->amenities),
            'price_adjustment' => $request->price_adjustment,
            'is_default' => $request->has('is_default'),
            'status' => $request->has('status'),
        ]);
        
        $notification = [
            'message' => 'Room Package Created Successfully',
            'alert-type' => 'success'
        ];
        
        return redirect()->route('room.packages')->with($notification);
    }
    
    public function edit($id)
    {
        $package = RoomPackage::findOrFail($id);
        return view('backend.room_package.edit', compact('package'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:room_packages,code,'.$id,
            'description' => 'nullable|string',
            'price_adjustment' => 'required|numeric',
            'inclusions' => 'nullable|array',
            'amenities' => 'nullable|array',
        ]);
        
        $package = RoomPackage::findOrFail($id);
        
        $package->update([
            'name' => $request->name,
            'code' => Str::upper($request->code),
            'description' => $request->description,
            'inclusions' => json_encode($request->inclusions ?? []),
            'amenities' => json_encode($request->amenities ?? []),
            'price_adjustment' => $request->price_adjustment,
            'is_default' => $request->has('is_default'),
            'status' => $request->has('status'),
        ]);
        
        $notification = [
            'message' => 'Room Package Updated Successfully',
            'alert-type' => 'success'
        ];
        
        return redirect()->route('room.packages')->with($notification);
    }
    
    public function destroy($id)
    {
        $package = RoomPackage::findOrFail($id);
        
        // Check if it's used in any bookings
        $hasBookings = $package->bookings()->exists();
        
        if ($hasBookings) {
            $notification = [
                'message' => 'Cannot delete package because it is associated with existing bookings',
                'alert-type' => 'error'
            ];
            
            return redirect()->back()->with($notification);
        }
        
        $package->delete();
        
        $notification = [
            'message' => 'Room Package Deleted Successfully',
            'alert-type' => 'success'
        ];
        
        return redirect()->route('room.packages')->with($notification);
    }
}