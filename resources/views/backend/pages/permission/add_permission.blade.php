@extends('admin.admin_dashboard')
@section('admin') 

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Add Permission </div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Add Permission</li>
							</ol>
						</nav>
					</div>
					 
				</div>
				<!--end breadcrumb-->
				<div class="container">
					<div class="main-body">
						<div class="row">
						 
    <div class="col-lg-12">
        
        <div class="card">

            <div class="card-body p-4">
                
       <form  class="row g-3" action="{{ route('store.permission') }}" method="post" enctype="multipart/form-data">
         @csrf

                 
    
    <div class="col-md-6">
        <label for="input1" class="form-label">Permission Name </label>
        <input type="text" name="name" class="form-control"   >
         
    </div>

    <div class="col-md-6">
        <label for="input1" class="form-label">Permission Group </label>
        <select name="group_name" class="form-select mb-3" aria-label="Default select example">
            <option selected="">Select Group </option>
            <option value="Manage Room Type">Manage Room Type</option>
            <option value="Room Management">Room Management</option>
            <option value="Booking Management">Booking Management</option>
            <option value="Beach Ticket">Beach Ticket</option>
            <option value="Role and Permission">Role and Permission </option>
        </select>
    </div>
    
 
                 
                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Save Changes </button>
                            
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>
						</div>
					</div>
				</div>
			</div>


            

@endsection