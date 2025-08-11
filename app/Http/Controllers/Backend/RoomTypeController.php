<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomType;
use App\Models\Hotel;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RoomTypeController extends Controller
{
    public function TlRoomList()
    {
        $hotel = Hotel::where('slug', 'tlresort')->first(); 
        $roomTypes = RoomType::where('hotel_id', $hotel->id)->orderBy('id','desc')->get();
        return view('backend.allroom.roomtype.view_roomtype', compact('roomTypes', 'hotel'));
    }

    public function KalicaaRoomList()
    {
        $hotel = Hotel::where('slug', 'kalicaav')->first();
        $roomTypes = RoomType::where('hotel_id', $hotel->id)->orderBy('id','desc')->get();
        return view('backend.allroom.roomtype.view_roomtype', compact('roomTypes', 'hotel'));
    }

    public function LbvRoomList()
    {
        $hotel = Hotel::where('slug', 'lbv')->first(); 
        $roomTypes = RoomType::where('hotel_id', $hotel->id)->orderBy('id','desc')->get();
        return view('backend.allroom.roomtype.view_roomtype', compact('roomTypes', 'hotel'));
    }


    public function LalassaRoomList()
    {
        $hotel = Hotel::where('slug', 'lalassabc')->first(); 
        $roomTypes = RoomType::where('hotel_id', $hotel->id)->orderBy('id','desc')->get();
        return view('backend.allroom.roomtype.view_roomtype', compact('roomTypes', 'hotel'));
    }


    public function AddRoomType($id) 
    {
        $hotel = Hotel::findOrFail($id); 
        return view('backend.allroom.roomtype.add_roomtype', compact('hotel'));
    }

    public function StoreRoomType(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $room_type_id = RoomType::insertGetId([
            'name' => $request->name,
            'hotel_id' => $id,
            'created_at' => Carbon::now(),
        ]);

        Room::insert([
            'room_type_id' => $room_type_id,
            'created_at' => Carbon::now(),
        ]);

        $notification = [
            'message' => 'RoomType Added Successfully',
            'alert-type' => 'success'
        ];


        $hotel = Hotel::findOrFail($id);
        
        switch($hotel->slug) {
            case 'tlresort':
                return redirect()->route('tlroom.list')->with($notification);
            case 'kalicaav':
                return redirect()->route('kalicaaroom.list')->with($notification);
            case 'lbv':
                return redirect()->route('lbvroom.list')->with($notification);
            case 'lalassabc':
                return redirect()->route('lalassaroom.list')->with($notification);
            default:
                return back()->with($notification);
        }
    }

    public function DeleteRoomType($id)
    {
        // Find the room type with all related data
        $roomType = RoomType::with(['room.facilities', 'room.multiImages'])->findOrFail($id);

        // Delete associated images from storage
        if ($roomType->room) {
            // Delete main image
            if ($roomType->room->image && file_exists(public_path('upload/rooming/'.$roomType->room->image))) {
                unlink(public_path('upload/rooming/'.$roomType->room->image));
            }

            // Delete multi images
            foreach ($roomType->room->multiImages as $image) {
                $path = public_path('upload/rooming/multi_img/'.$image->multi_images);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }

        // Delete database records
        DB::transaction(function() use ($roomType) {
            // Delete facilities
            if ($roomType->room) {
                $roomType->room->facilities()->delete();
                $roomType->room->multiImages()->delete();
                $roomType->room()->delete();
            }
            
            // Delete the room type
            $roomType->delete();
        });

        $notification = [
            'message' => 'Room Type and All Related Data Deleted Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }
}