<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room; 
use \claviska\SimpleImage;
use App\Models\Facilities;
use App\Models\MultiImage;
use App\Models\RoomNumber;
use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    public function EditRoom($id) 
    {
        $editData = Room::with(['type', 'facilities', 'multiImages'])->findOrFail($id);
        
        // Get all room types for the dropdown
        $roomTypes = RoomType::all();
        
        $detail_facility = $editData->facilities;
        $multiimgs = $editData->multiImages;
        $allroomNum = RoomNumber::where('rooms_id', $id)->get();
        
        return view('backend.allroom.rooms.edit_rooms', compact(
            'editData',
            'roomTypes',
            'detail_facility',
            'multiimgs',
            'allroomNum'
        ));
    }

    public function UpdateRoom(Request $request, $id)
    {
        // Enable query logging for debugging
        DB::enableQueryLog();
        
        try {
            // Debug: Log incoming data
            Log::info('UpdateRoom called with data:', $request->all());
            
            // 1. Validate the request
            $validated = $request->validate([
                'room_type_id' => 'required|integer',
                'description' => 'required|string',
                'room_capacity' => 'required|string',
                'guests_total' => 'required|integer',
                'price' => 'required|string',
                'size' => 'string',
                'bed_type' => 'string',
                'discount' => 'nullable|numeric',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            ]);

            Log::info('Validation passed');

            // 2. Find the room
            $room = Room::findOrFail($id);
            Log::info('Room found:', ['id' => $id, 'current_data' => $room->toArray()]);

            // 3. Start database transaction
            DB::beginTransaction();

            // 4. Prepare the update data
            $updateData = [
                'room_type_id' => $request->input('room_type_id'),
                'description' => $request->input('description'),
                'guests_total' => $request->input('guests_total'),
                'room_capacity' => $request->input('room_capacity'),
                'price' => $request->input('price'),
                'size' => $request->input('size'),
                'bed_type' => $request->input('bed_type'),
                'discount' => $request->input('discount', 0),
                'status' => $request->input('status', 1),
            ];

            Log::info('Update data prepared:', $updateData);

            // 5. Handle image upload
            if ($request->hasFile('image')) {
                Log::info('Processing main image upload');
                
                $image = $request->file('image');
                $filename = 'room_'.time().'.'.$image->getClientOriginalExtension();
                
                // Store the image
                $image->move(public_path('upload/rooming'), $filename);
                $updateData['image'] = $filename;

                // Delete old image if exists
                if ($room->image && file_exists(public_path('upload/rooming/'.$room->image))) {
                    unlink(public_path('upload/rooming/'.$room->image));
                }
                
                Log::info('Main image uploaded:', ['filename' => $filename]);
            }

            // 6. Perform the room update
            $roomUpdateResult = $room->update($updateData);
            
            if (!$roomUpdateResult) {
                throw new \Exception('Failed to update room data');
            }
            
            Log::info('Room updated successfully');

            // 7. Handle facilities
            if (!empty($request->facilities_name) || !empty($request->basic_facilities_name)) {
                Log::info('Processing facilities');
                
                // Delete existing facilities
                $deletedFacilities = Facilities::where('rooms_id', $id)->delete();
                Log::info('Deleted facilities count:', ['count' => $deletedFacilities]);
                
                $facilities = $request->facilities_name ?? $request->basic_facilities_name;
                
                foreach ((array)$facilities as $facility) {
                    if (!empty($facility)) {
                        $facilityResult = Facilities::create([
                            'rooms_id' => $id,
                            'facilities_name' => $facility
                        ]);
                        
                        if (!$facilityResult) {
                            throw new \Exception('Failed to create facility: ' . $facility);
                        }
                        
                        Log::info('Facility created:', ['facility' => $facility]);
                    }
                }
            } else {
                DB::rollback();
                
                $notification = [
                    'message' => 'Sorry! No Facilities Selected',
                    'alert-type' => 'error'
                ];
                return redirect()->back()->with($notification);
            }
            
            // 8. Handle multi images
            if ($request->hasFile('multi_images')) {
                Log::info('Processing multi images');
                
                // Delete existing images
                $existingImages = MultiImage::where('rooms_id', $id)->get();
                foreach ($existingImages as $img) {
                    $imagePath = public_path('upload/rooming/multi_img/'.$img->multi_images);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                    $img->delete();
                }
                
                Log::info('Deleted existing multi images:', ['count' => $existingImages->count()]);

                // Upload new images
                foreach ($request->file('multi_images') as $file) {
                    $imgName = 'multi_'.time().'_'.$file->getClientOriginalName();
                    $file->move(public_path('upload/rooming/multi_img'), $imgName);
                    
                    $multiImageResult = MultiImage::create([
                        'rooms_id' => $id,
                        'multi_images' => $imgName
                    ]);
                    
                    if (!$multiImageResult) {
                        throw new \Exception('Failed to create multi image: ' . $imgName);
                    }
                    
                    Log::info('Multi image created:', ['filename' => $imgName]);
                }
            }

            // Commit transaction
            DB::commit();
            
            // Log all executed queries for debugging
            $queries = DB::getQueryLog();
            Log::info('Executed queries:', $queries);

            $notification = [
                'message' => 'Room Updated Successfully',
                'alert-type' => 'success'
            ];

            Log::info('Room update completed successfully');
            return redirect()->back()->with($notification);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            Log::error('Validation error:', ['errors' => $e->errors()]);
            
            $errors = [];
            foreach ($e->errors() as $field => $messages) {
                $errors = array_merge($errors, $messages);
            }
            
            $notification = [
                'message' => 'Validation Error: ' . implode(', ', $errors),
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification)->withInput();
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Room update error:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification = [
                'message' => 'Error updating room: ' . $e->getMessage(),
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification)->withInput();
        }
    }

    public function deleteGalleryImage($id)
    {
        try {
            $image = MultiImage::findOrFail($id);
            
            // Delete file from storage
            $imagePath = public_path('upload/rooming/multi_img/'.$image->multi_images);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            // Delete record from database
            $image->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function StoreRoomNumber(Request $request,$id){

        $data = new RoomNumber();
        $data->rooms_id = $id;
        $data->room_type_id = $request->room_type_id;
        $data->room_num = $request->room_num;
        $data->status = $request->status;
        $data->save();

        $notification = array(
            'message' => 'Room Number Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

    }//End Method 



    public function EditRoomNumber($id){

        $editroomnum = RoomNumber::find($id);
        return view('backend.allroom.rooms.edit_room_num',compact('editroomnum'));

    }//End Method 

    public function UpdateRoomNumber(Request $request, $id){

        $data = RoomNumber::find($id);
        $data->room_num = $request->room_num;
        $data->status = $request->status;
        $data->save();

       $notification = array(
            'message' => 'Room Number Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

    }//End Method 


    public function DeleteRoomNumber($id){

        RoomNumber::find($id)->delete();

        $notification = array(
            'message' => 'Room Number Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

    }//End Method
}