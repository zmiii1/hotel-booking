@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>



<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{ $hotel->name }}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active">Add Room Type</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <form id="myForm" action="{{ route('store.room.type', ['id' => $hotel->id]) }}" method="post">
                            @csrf
                            <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <h6 class="mb-0">Room Type Name</h6>
                                    </div>
                                    <div class="col-sm-8 text-secondary">
                                        <input type="text" name="name" class="form-control" required />
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-8 text-secondary">
                                        <button type="submit" class="btn btn-primary px-4">Save</button>
                                        <a href="{{ url()->previous() }}" class="btn btn-secondary px-4">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        // Character counter
        $('input[name="name"]').on('input', function() {
            var remaining = 255 - $(this).val().length;
            $('#name-counter').text(remaining + ' characters remaining');
        });

        // Form validation
        $('#myForm').validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 255
                }
            },
            messages: {
                name: {
                    required: "Please enter room type name",
                    maxlength: "Name must not exceed 255 characters"
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.text-secondary').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endsection