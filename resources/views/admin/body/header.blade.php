

<header>
	<div class="topbar d-flex align-items-center">
		<nav class="navbar navbar-expand gap-3">
			<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
			</div>

				<div class="position-relative search-bar d-lg-block d-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
				<input class="form-control px-5" disabled type="search" placeholder="Search">
				<span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-5"></i></span>
				</div>


				<div class="top-menu ms-auto">
				<ul class="navbar-nav align-items-center gap-1">
					<li class="nav-item mobile-search-icon d-flex d-lg-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
						<a class="nav-link" href="avascript:;"><i class='fa fa-search'></i>
						</a>
					</li>
				</ul>
			</div>

			@php
				$id = Auth::user()->id;
				$profileData = App\Models\User::find($id);
			@endphp



			<div class="user-box dropdown px-3">
				<a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
				<img src="{{ (!empty($profileData->photo)) ? url('upload/admin_images/'.$profileData->photo) : url('upload/default_image.jpeg')}}" class="user-img" alt="user avatar">
					<div class="user-info">
						<p class="user-name mb-0">{{ $profileData->name }}</p>
						<p class="designattion mb-0">{{ $profileData->email }}</p>
					</div>
				</a>
				<ul class="dropdown-menu dropdown-menu-end">
					<li><a class="dropdown-item d-flex align-items-center" href="{{ route('admin.profile')}}"><i class="bx bx-user fs-5"></i><span>Profile</span></a>
					</li>
					<li>
						<div class="dropdown-divider mb-0"></div>
					</li>
					<li><a class="dropdown-item d-flex align-items-center" href="{{ route('admin.logout') }}"><i class="bx bx-log-out-circle"></i><span>Logout</span></a>
					</li>
				</ul>
			</div>
		</nav>
	</div>
</header>