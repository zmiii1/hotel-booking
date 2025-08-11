<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="{{asset('backend/assets/images/logotl (1).png')}}" type="image/png" />
	<link href="{{asset('backend/assets/plugins/simplebar/css/simplebar.css')}}" rel="stylesheet" />
	<link href="{{asset('backend/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" />
	<link href="{{asset('backend/assets/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet" />
	<link href="{{asset('backend/assets/css/pace.min.css')}}" rel="stylesheet" />
	<script src="{{asset('backend/assets/js/pace.min.js')}}"></script>
	<link href="{{asset('backend/assets/css/bootstrap.min.css')}}" rel="stylesheet">
	<link href="{{asset('backend/assets/css/bootstrap-extended.css')}}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="{{asset('backend/assets/css/app.css')}}" rel="stylesheet">
	<link href="{{asset('backend/assets/css/icons.css')}}" rel="stylesheet">
	<title>Hotel Admin Login</title>
</head>

<body class="">
	<div class="wrapper">
		<div class="section-authentication-cover">
			<div class="container">
				<div class="row g-0 justify-content-center">
					<div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
						<div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
							<div class="card-body p-sm-5">
								<div class="mb-3 text-center">
									<img src="{{asset('backend/assets/images/logotl (1).png')}}" width="60" alt="">
								</div>
								<div class="text-center mb-4">
									<h5>Hotel Admin Login</h5>
									<p class="mb-0">Please log in to your account</p>
								</div>

								{{-- SHOW ERROR MESSAGES --}}
								@if ($errors->any())
									<div class="alert alert-danger">
										@foreach ($errors->all() as $error)
											<p class="mb-0">{{ $error }}</p>
										@endforeach
									</div>
								@endif

								<div class="form-body">
									{{-- FIX: Change to admin.process.login and use username --}}
									<form class="row g-3" method="POST" action="{{ route('admin.process.login') }}">
										@csrf
										
										{{-- CHANGE: Email to Username --}}
										<div class="col-12">
											<label for="username" class="form-label">Username</label>
											<input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
												   id="username" placeholder="Enter your username" value="{{ old('username') }}" required>
											@error('username')
												<span class="text-danger">{{ $message }}</span>
											@enderror
										</div>

										<div class="col-12">
											<label for="password" class="form-label">Password</label>
											<div class="input-group" id="show_hide_password">
												<input type="password" name="password" class="form-control border-end-0 @error('password') is-invalid @enderror" 
													   id="password" placeholder="Enter Password" required>
												<a href="javascript:;" class="input-group-text bg-transparent">
													<i class="bx bx-hide"></i>
												</a>
											</div>
											@error('password')
												<span class="text-danger">{{ $message }}</span>
											@enderror
										</div>

										<div class="col-md-6">
											<div class="form-check form-switch">
												<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
												<label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
											</div>
										</div>

										{{-- REMOVE: Forgot password link --}}
										<div class="col-md-6 text-end">
											<small class="text-muted">Contact Super Admin for password reset</small>
										</div>

										<div class="col-12">
											<div class="d-grid">
												<button type="submit" class="btn btn-primary">Sign in</button>
											</div>
										</div>

										{{-- REMOVE: Sign up link --}}
										<div class="col-12">
											<div class="text-center">
												<small class="text-muted">
													Access provided by Super Admin only
												</small>
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
	</div>

	<script src="{{asset('backend/assets/js/bootstrap.bundle.min.js')}}"></script>
	<script src="{{asset('backend/assets/js/jquery.min.js')}}"></script>
	<script src="{{asset('backend/assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
	<script src="{{asset('backend/assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
	<script src="{{asset('backend/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
	
	{{-- Password toggle script --}}
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>
	<script src="{{asset('backend/assets/js/app.js')}}"></script>
</body>
</html>

