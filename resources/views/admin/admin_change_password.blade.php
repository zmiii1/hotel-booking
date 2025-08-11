@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Admin Change Password</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Admin Change Password</li>
							</ol>
						</nav>
					</div>
					<div class="ms-auto">
						<div class="btn-group">
							<button type="button" class="btn btn-primary">Settings</button>
							<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
							</button>
							<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">	<a class="dropdown-item" href="javascript:;">Action</a>
								<a class="dropdown-item" href="javascript:;">Another action</a>
								<a class="dropdown-item" href="javascript:;">Something else here</a>
								<div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Separated link</a>
							</div>
						</div>
					</div>
				</div>
				<!--end breadcrumb-->
				<div class="container">
					<div class="main-body">
						<div class="row">
							<div class="col-lg-4">
								<div class="card">
									<div class="card-body">
										<div class="d-flex flex-column align-items-center text-center">
											<img src="{{ (!empty($profileData->photo)) ? url('upload/admin_images/'.$profileData->photo) : url('upload/default_image.jpeg')}}" alt="Admin" class="rounded-circle p-1 bg-primary" width="110">
											<div class="mt-3">
												<h4>{{ $profileData->name}}</h4>
												<p class="text-secondary mb-1">{{ $profileData->email}}</p>
											</div>
										</div>
										<hr class="my-4" />
										<ul class="list-group list-group-flush">
										</ul>
									</div>
								</div>
							</div>
							<div class="col-lg-8">
								<div class="card">


                                    <form action="{{ route('admin.password.update') }}" method="post" enctype="multipart/form-data">
                                        @csrf

									<div class="card-body">

										<div class="row mb-3">
											<div class="col-sm-3">
												<h6 class="mb-0">Old Password</h6>
											</div>
											<div class="col-sm-9 text-secondary">
												<input type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror" id="old_password"/>
                                                @error('old_password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
											</div>
										</div>
                                    

										<div class="row mb-3">
											<div class="col-sm-3">
												<h6 class="mb-0">New Password</h6>
											</div>
											<div class="col-sm-9 text-secondary">
												<input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" id="new_password"/>
                                                @error('new_password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
											</div>
										</div>

                                        <div class="row mb-3">
											<div class="col-sm-3">
												<h6 class="mb-0">Confirm New Password</h6>
											</div>
											<div class="col-sm-9 text-secondary">
												<input type="password" name="new_password_confirmation" 
                                                class="form-control" id="new_password_confirmation"/>
											</div>
										</div>
                                     
                                        
										<div class="row">
											<div class="col-sm-3"></div>
											<div class="col-sm-9 text-secondary">
												<input type="submit" class="btn btn-primary px-4" value="Save Changes" />
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
@endsection