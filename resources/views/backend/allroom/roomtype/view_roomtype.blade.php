
@extends('admin.admin_dashboard')
@section('admin')

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">
@stack('styles')

<div class="page-content">
    <!--breadcrumb-->
    <div class="ms-auto">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"style="color: #DC1C6C;"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Manage Room</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('add.room.type', ['id' => $hotel->id]) }}" class="btn btn-outline-primary px-5 radius-30">Add Room Type</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->
    
    <h6 class="mb-0 text-uppercase">{{ $hotel->name }} - Room Type</h6>
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roomTypes as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                                <td> <img src="{{ (!empty($item->room->image)) ? url('upload/rooming/'.$item->room->image) : url('upload/no_image.jpg') }}" alt="" style="width: 50px; height:30px;" >   </td>
                            <td>{{ $item->name }}</td>
                            <td>
                                @php
                                    $rooms = App\Models\Room::where('room_type_id', $item->id)->get();
                                @endphp
                                @foreach ($rooms as $room)
                                    <div>
                                        {{ $room->name }}
                                        <a href="{{ route('edit.room', $room->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('delete.room.type', $item->id) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger px-3 radius-30" 
                                                    onclick="return confirm('Are you sure you want to delete this room type and all related data?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection