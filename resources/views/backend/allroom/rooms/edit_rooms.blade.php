@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="page-content">
	<div class="container">
		<div class="main-body">
			<div class="row">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-primary" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#primaryhome" role="tab" aria-selected="true">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class="bx bx-home font-18 me-1"></i>
                                        </div>
                                        <div class="tab-title">Manage Room</div>
                                    </div>
                                </a>
                            </li>
                            
                            <li class="nav-items" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#primaryprofile"
                                role="tab" aria-selected="false" tabindex="-1">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class="bx bx-user-pin font-18 me-1"></i>
                                        </div>
                                        <div class="tab-title">Room Number</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content py-3">
                            <div class="tab-pane fade show active" id="primaryhome" role="tabpanel">

                                <div class="col-xl-12 mx-auto">

                                    <div class="card">
                                        <div class="card-body p-4">
                                            <h5 class="mb-4">Update Room</h5>
                                            <form class="row g-3" action="{{ route('update.room', $editData->id) }}" method="post" enctype="multipart/form-data">
                                                @csrf

                                                <div class="col-md-4">
                                                    <label for="input1" class="form-label">Room Type Name</label>
                                                    <select name="room_type_id" class="form-control" id="input1">
                                                        @foreach($roomTypes as $type)
                                                            <option value="{{ $type->id }}" 
                                                                {{ $editData->room_type_id == $type->id ? 'selected' : '' }}>
                                                                {{ $type->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="input11" class="form-label">Description</label>
                                                    <textarea name="description" class="form-control" id="input2" placeholder="Address ..." rows="3">{!! $editData->description !!}</textarea>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input2" class="form-label">Room Capacity</label>
                                                    <input type="text" name="room_capacity" class="form-control" id="input2" value="{{$editData->room_capacity}}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input2" class="form-label">Guests Total</label>
                                                    <input type="text" name="guests_total" class="form-control" id="input2" value="{{$editData->guests_total}}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="input3" class="form-label">Main Image</label>
                                                    <input type="file" name="image" class="form-control" id="image">

                                                    <img id="showImage" src="{{ (!empty($editData->image)) ? url('upload/rooming/'.$editData->image): url('upload/no_image.jpg') }}" alt="Admin" class="bg-primary" width="60">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="input4" class="form-label">Gallery Image</label>
                                                    <input type="file" name="multi_images[]" class="form-control" multiple id="multiImg" accept="image/jpeg, image/jpg, image/gif, image/png">
                                                    
                                                    <div class="current-gallery-images mt-2 d-flex flex-wrap" style="gap: 15px;">
                                                        @if($multiimgs->count() > 0)
                                                            @foreach($multiimgs as $img)
                                                                <div class="position-relative" style="width: 60px; height: 60px;">
                                                                    <img src="{{ asset('upload/rooming/multi_img/'.$img->multi_images) }}" 
                                                                        class="img-thumbnail w-100 h-100" 
                                                                        style="object-fit: cover;">
                                                                    <button type="button" 
                                                                            class="position-absolute bg-white rounded-circle p-0 border-0 delete-gallery-image"
                                                                            style="width: 18px; height: 18px; top: -5px; right: -5px;"
                                                                            data-id="{{ $img->id }}"
                                                                            data-url="{{ route('delete.gallery.image', $img->id) }}">
                                                                        <span style="font-size: 10px; line-height: 1; color: #dc3545;">×</span>
                                                                    </button>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <p class="text-muted">No gallery images uploaded yet</p>
                                                        @endif
                                                    </div>
                                                    <div class="preview-gallery-images mt-2 d-flex flex-wrap" style="gap: 15px;" id="preview_img"></div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input1" class="form-label">Room Price</label>
                                                    <input type="text" name="price" class="form-control" id="input1" value="{{ $editData->price}}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input1" class="form-label">Size</label>
                                                    <input type="text" name="size" class="form-control" id="input1" value="{{ $editData->size}}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="input7" class="form-label">Bed Type</label>
                                                    <select name="bed_type" class="form-select">
                                                        <option value="Single Bed" {{ $editData->bed_type == 'Single Bed' ? 'selected' : '' }}>Single Bed</option>
                                                        <option value="Double Bed" {{ $editData->bed_type == 'Double Bed' ? 'selected' : '' }}>Double Bed</option>
                                                        <option value="King Bed" {{ $editData->bed_type == 'King Bed' ? 'selected' : '' }}>King Bed</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input2" class="form-label">Discount (%)</label>
                                                    <input type="text" name="discount" class="form-control" id="input2" value="{{$editData->discount}}">
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-md-12 mb-3">
                                                    @forelse ($detail_facility as $item)
                                                    <div class="detail_facility_section_remove" id="detail_facility_section_remove">
                                                        <div class="row add_item">
                                                            <div class="col-md-8">
                                                                <label for="facilities_name" class="form-label">Room Facilities</label>
                                                                <select name="facilities_name[]" id="facilities_name" class="form-control">
                                                                    <option value="">Select Facility</option>
                                                                    @foreach([
                                                                        'Digital TV',
                                                                        'Smart TV',
                                                                        'Wi-Fi',
                                                                        'Air conditioning',
                                                                        'Coffee machine',
                                                                        'Refrigerator',
                                                                        'Waterheater',
                                                                        'In-room telephone',
                                                                        'Shower',
                                                                        'Bathroom',
                                                                        'Slippers',
                                                                        'Closet',
                                                                        'Mirror',
                                                                        'Wardrobe',
                                                                        'Clothes rack',
                                                                        'Chair',
                                                                        'Bedside table',
                                                                        'Garden view',
                                                                        'Double bed',
                                                                        'Toiletries',
                                                                        'Two twin beds or one double bed',
                                                                        'Private pool',
                                                                        'Drinking water',
                                                                        'Dining area',
                                                                        'Sofa',
                                                                        'Mineral water',
                                                                        'Tea and coffee kit',
                                                                        'Kettle',
                                                                        'Mini-Kitchen',
                                                                        'Canopy'
                                                                    ] as $facility)
                                                                        <option value="{{ $facility }}" {{ $item->facilities_name == $facility ? 'selected' : '' }}>
                                                                            {{ $facility }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group" style="padding-top: 30px;">
                                                                    <a class="btn btn-success addeventmore"><i class="lni lni-circle-plus"></i></a>
                                                                    <span class="btn btn-danger btn-sm removeeventmore"><i class="lni lni-circle-minus"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @empty
                                                    <div class="facility_section_remove" id="facility_section_remove">
                                                        <div class="row add_item">
                                                            <div class="col-md-6">
                                                                <label for="basic_facilities_name" class="form-label">Room Facilities</label>
                                                                <select name="basic_facilities_name[]" id="basic_facilities_name" class="form-control">
                                                                    <option value="">Select Facility</option>
                                                                    @foreach([
                                                                        'Digital TV',
                                                                        'Smart TV',
                                                                        'Wi-Fi',
                                                                        'Air conditioning',
                                                                        'Coffee machine',
                                                                        'Refrigerator',
                                                                        'In-room telephone',
                                                                        'Shower',
                                                                        'Bathroom',
                                                                        'Slippers',
                                                                        'Closet',
                                                                        'Mirror',
                                                                        'Wardrobe',
                                                                        'Clothes rack',
                                                                        'Chair',
                                                                        'Bedside table',
                                                                        'Waterheater',
                                                                        'Garden view',
                                                                        'Double bed',
                                                                        'Toiletries',
                                                                        'Two twin beds or one double bed',
                                                                        'Private pool',
                                                                        'Drinking water',
                                                                        'Dining area',
                                                                        'Sofa',
                                                                        'Mineral water',
                                                                        'Tea and coffee kit',
                                                                        'Kettle',
                                                                        'Mini-Kitchen',
                                                                        'Canopy'
                                                                    ] as $facility)
                                                                        <option value="{{ $facility }}">{{ $facility }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group" style="padding-top: 30px;">
                                                                    <a class="btn btn-success addeventmore"><i class="lni lni-circle-plus"></i></a>
                                                                    <span class="btn btn-danger removeeventmore"><i class="lni lni-circle-minus"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforelse
                                            </div> 
                                        </div>
                                        <div class="col-md-12">
                                            <div class="d-md-flex d-grid align-items-center gap-3">
                                                <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- // end of primaryhome --}}                       
                    <div class="tab-pane fade" id="primaryprofile" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <a class="card-title btn btn-primary float-right" onclick="addRoomNum()" id="addRoomNum" > 
                                        <i class="lni lni-plus">Add New</i>
                                    </a>
                                    <div class="roomnumHide" id="roomnumHide">
                                        <form action="{{ route('store.room.num',$editData->id) }}" method="post">
                                            @csrf
                                            
                                            <input type="hidden" name="room_type_id" value="{{ $editData->room_type_id }}">

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="input2" class="form-label">Room Number</label>
                                                    <input type="text" name="room_num" class="form-control" id="input2">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input7" class="form-label">Status</label>
                                                    <select name="status" id="input7" class="form-select">
                                                        <option selected="">Select Status...</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Inactive">Inactive</option>
                                                    </select>
                                                </div> 
                                                <div class="col-md-4">
                                                    <button type="submit" class="btn btn-success" style="margin-top: 28px;">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <table class="table mb-0 table-striped" id="roomview">
                                        <thead>
                                            <tr>
                                                <th scope="col">Room Number</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($allroomNum as $item)
                                            <tr>
                                                <td>{{ $item->room_num }}</td>
                                                <td>{{ $item->status }}</td>
                                                <td>
                                                    <a href="{{ route('edit.roomnum',$item->id) }}" class="btn btn-warning px-3 radius-30"> Edit</a>
                                                    <a href="{{ route('delete.roomnum',$item->id) }}" class="btn btn-danger px-3 radius-30" id="delete"> Delete</a>  
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


            <div class="tab-pane fade" id="primaryprofile" role="tabpanel">
                <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</p>
            </div>
            </div> 
            {{-- // end PrimaryProfile --}}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
		$(document).ready(function(){
			$('#image').change(function(e){
				var reader = new FileReader();
				reader.onload = function(e){
					$('#showImage').attr('src',e.target.result);
				}
				reader.readAsDataURL(e.target.files['0']);
			});
		});
	</script>

    <!--------===Show MultiImage ========------->
<script>
$(document).ready(function(){
    // Preview newly selected images
    $('#multiImg').on('change', function() {
        $('#preview_img').empty();
        
        if (this.files && this.files.length > 0) {
            Array.from(this.files).forEach((file, index) => {
                if (/\.(jpe?g|png|gif)$/i.test(file.name)) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const wrapper = $('<div class="position-relative" style="width: 60px; height: 60px;">');
                        
                        $('<img>')
                            .attr('src', e.target.result)
                            .addClass('img-thumbnail w-100 h-100')
                            .css('object-fit', 'cover')
                            .appendTo(wrapper);
                            
                        $('<button>')
                            .addClass('position-absolute bg-white rounded-circle p-0 border-0 delete-new-image')
                            .css({
                                'width': '18px',
                                'height': '18px',
                                'top': '-5px',
                                'right': '-5px'
                            })
                            .html('<span style="font-size: 10px; line-height: 1; color: #dc3545;">×</span>')
                            .on('click', function() {
                                // Remove from file input
                                const dt = new DataTransfer();
                                const input = document.getElementById('multiImg');
                                
                                Array.from(input.files)
                                    .filter((_, i) => i !== index)
                                    .forEach(file => dt.items.add(file));
                                
                                input.files = dt.files;
                                $(this).parent().remove();
                            })
                            .appendTo(wrapper);
                            
                        $('#preview_img').append(wrapper);
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        }
    });
    
    // Delete existing images
    $(document).on('click', '.delete-gallery-image', function() {
        if (confirm('Are you sure you want to delete this image?')) {
            const button = $(this);
            $.ajax({
                url: button.data('url'),
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: () => button.closest('.position-relative').fadeOut(),
                error: () => alert('Error deleting image')
            });
        }
    });
});
</script>

<!--========== Start of add Facilities ==============-->
<div style="visibility: hidden">
   <div class="whole_extra_item_add" id="whole_extra_item_add">
      <div class="detail_facility_section_remove" id="detail_facility_section_remove">
         <div class="container mt-2">
            <div class="row">
               <div class="form-group col-md-6">
                  <label for="detail_facilities_name">Room Facilities</label>
                  <select name="facilities_name[]" id="detail_facilities_name" class="form-control">
                    <option value="">Select Facility</option>
                    @foreach([
                        'Digital TV',
                        'Smart TV',
                        'Wi-Fi',
                        'In-room Telephone',
                        'Air conditioning',
                        'Coffee machine',
                        'Refrigerator',
                        'Bathroom',
                        'Shower',
                        'Waterheater',
                        'Toiletries',
                        'Slippers',
                        'Garden view',
                        'Double bed',
                        'Closet',
                        'Mirror',
                        'Wardrobe',
                        'Clothes rack',
                        'Chair',
                        'Two twin beds or one double bed',
                        'Private pool',
                        'Drinking water',
                        'Dining area',
                        'Sofa',
                        'Bedside table',
                        'Mineral water',
                        'Tea and coffee kit',
                        'Kettle',
                        'Mini-Kitchen',
                        'Canopy'
                    ] as $facility)
                        <option value="{{ $facility }}">{{ $facility }}</option>
                    @endforeach
                </select>
               </div>
               <div class="form-group col-md-6" style="padding-top: 20px">
                  <span class="btn btn-success addeventmore"><i class="lni lni-circle-plus"></i></span>
                  <span class="btn btn-danger removeeventmore"><i class="lni lni-circle-minus"></i></span>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
    $('#roomnumHide').hide();
    $('#roomview').show();

    function addRoomNum(){
        $('#roomnumHide').show();
        $('#roomview').hide();
        $('#addRoomNum').hide();
    }

</script>

<script type="text/javascript">
   $(document).ready(function(){
    var counter = 0;
    $(document).on("click", ".addeventmore", function(){
        var whole_extra_item_add = $("#whole_extra_item_add").html();
        $(this).closest(".add_item").append(whole_extra_item_add);
        counter++;
    });
    $(document).on("click", ".removeeventmore", function(event){
        $(this).closest(".detail_facility_section_remove").remove();
        counter -= 1;
    });
});
</script>
<!--========== End of Facilities ==============-->

@endsection