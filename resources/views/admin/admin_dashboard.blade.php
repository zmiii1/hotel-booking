<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hotel Admin Dashboard</title>
    
    <!-- CSS includes -->
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/css/icons.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/css/sidebar-menu.css') }}" rel="stylesheet">
    

</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <img src="{{ asset('backend/assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
                </div>
                <div>
                    <h4 class="logo-text">Hotel Admin</h4>
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i></div>
            </div>
            
            <!--navigation-->
            <ul class="metismenu" id="menu">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>
                
                @role('Super Admin')
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="bx bx-category"></i></div>
                        <div class="menu-title">Admin Management</div>
                    </a>
                    <ul>
                        <li><a href="{{ route('all.admin') }}"><i class='bx bx-radio-circle'></i>All Admin</a></li>
                        <li><a href="{{ route('add.admin') }}"><i class='bx bx-radio-circle'></i>Add Admin</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="bx bx-shield"></i></div>
                        <div class="menu-title">Role & Permission</div>
                    </a>
                    <ul>
                        <li><a href="{{ route('all.roles') }}"><i class='bx bx-radio-circle'></i>All Roles</a></li>
                        <li><a href="{{ route('all.roles.permission') }}"><i class='bx bx-radio-circle'></i>Roles in Permission</a></li>
                        <li><a href="{{ route('all.permission') }}"><i class='bx bx-radio-circle'></i>All Permission</a></li>
                    </ul>
                </li>
                @endrole
            
            </ul>
            <!--end navigation-->
        </div>
        <!--end sidebar wrapper -->

        <!--start header -->
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand gap-3">
                    <div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>
                    
                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center gap-1">
                            <li class="nav-item dropdown dropdown-app">
                                <div class="dropdown-menu dropdown-menu-end p-0">
                                    <div class="app-container p-2 my-2">
                                        <!-- Apps content -->
                                    </div>
                                </div>
                            </li>

                            <li class="nav-item dropdown dropdown-large">
                                <div class="dropdown-menu dropdown-menu-end">
                                    <!-- Notifications -->
                                </div>
                            </li>

                            <li class="nav-item dropdown dropdown-user-setting">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                                    <div class="user-setting">
                                        <img src="{{ asset('backend/assets/images/avatars/avatar-1.png') }}" class="user-img" alt="">
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                            <div class="d-flex flex-row align-items-center gap-2">
                                                <img src="{{ asset('backend/assets/images/avatars/avatar-1.png') }}" alt="" class="rounded-circle" width="54" height="54">
                                                <div class="">
                                                    <h6 class="mb-0 dropdown-user-name">{{ Auth::user()->name }}</h6>
                                                    <small class="mb-0 dropdown-user-designation text-secondary">{{ Auth::user()->getRoleNames()->first() }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                            <div class="d-flex align-items-center">
                                                <div class=""><i class="bx bx-user fs-5"></i></div>
                                                <div class="ms-3"><span>Profile</span></div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.change.password') }}">
                                            <div class="d-flex align-items-center">
                                                <div class=""><i class="bx bx-cog fs-5"></i></div>
                                                <div class="ms-3"><span>Settings</span></div>
                                            </div>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.logout') }}">
                                            <div class="d-flex align-items-center">
                                                <div class=""><i class="bx bx-log-out-circle"></i></div>
                                                <div class="ms-3"><span>Logout</span></div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <!--end header -->

        <!--wrapper-->
		<div class="wrapper">
        <!--sidebar wrapper -->
        @include('admin.body.sidebar')
        <!--end sidebar wrapper -->
        
        <!--start header -->
        @include('admin.body.header')
        <!--end header -->
        
        <!--start page wrapper -->
        <div class="page-wrapper">
            @yield('admin')    <!-- CONTENT MASUK DISINI -->
        </div>
        <!--end page wrapper -->
        
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->

        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->

        @include('admin.body.footer')
		</div>
		<!--end wrapper-->

        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->

        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->

        <footer class="page-footer">
            <p class="mb-0">Copyright © 2025. All right reserved.</p>
        </footer>
    </div>
    <!--end wrapper-->
	    
    <!-- JS includes - CORRECT ORDER -->
    <script src="{{ asset('backend/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('backend/assets/js/app.js') }}"></script>
    
    <!-- ✅ SMOOTH DROPDOWN ANIMATION SCRIPT -->
    <script>
    $(document).ready(function() {
        console.log('🎯 Initializing smooth dropdown animations...');
        
        // ============================================
        // SMOOTH DROPDOWN WITH ARROW ROTATION
        // ============================================
        
        function initSmoothDropdowns() {
            console.log('Setting up smooth dropdowns...');
            
            // First, hide all dropdowns and reset arrows
            $('.sidebar-wrapper .metismenu ul').hide().removeClass('mm-show');
            $('.sidebar-wrapper .metismenu li').removeClass('mm-active mm-show');
            $('.sidebar-wrapper .metismenu .dropdown-arrow i').removeClass('rotated');
            
            // Remove any existing MetisMenu to avoid conflicts
            if ($('.metismenu').data('metisMenu')) {
                $('.metismenu').metisMenu('dispose');
            }
            
            // Setup custom click handlers for smooth animation
            $('.sidebar-wrapper .metismenu > li > a.has-arrow').off('click.smooth').on('click.smooth', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const $this = $(this);
                const $parent = $this.parent();
                const $submenu = $parent.find('> ul');
                const $arrow = $this.find('.dropdown-arrow i');
                const isOpen = $parent.hasClass('mm-active');
                
                const menuTitle = $this.find('.menu-title').text();
                console.log('🎯 Clicked:', menuTitle, '| Current state:', isOpen ? 'OPEN' : 'CLOSED');
                
                // Close all other dropdowns smoothly
                $('.sidebar-wrapper .metismenu > li').not($parent).each(function() {
                    const $otherParent = $(this);
                    const $otherSubmenu = $otherParent.find('> ul');
                    const $otherArrow = $otherParent.find('.dropdown-arrow i');
                    
                    if ($otherParent.hasClass('mm-active')) {
                        console.log('🔄 Closing:', $otherParent.find('.menu-title').text());
                        
                        // Smooth close animation
                        $otherSubmenu.slideUp(300, function() {
                            $otherParent.removeClass('mm-active mm-show');
                        });
                        
                        // Rotate arrow back to right
                        $otherArrow.removeClass('rotated');
                    }
                });
                
                // Toggle current dropdown with smooth animation
                if (isOpen) {
                    console.log('🔼 Closing current:', menuTitle);
                    
                    // Close current dropdown
                    $submenu.slideUp(300, function() {
                        $parent.removeClass('mm-active mm-show');
                    });
                    
                    // Rotate arrow back to right (smooth)
                    $arrow.removeClass('rotated');
                    
                } else {
                    console.log('🔽 Opening:', menuTitle);
                    
                    // Open current dropdown
                    $parent.addClass('mm-active mm-show');
                    $submenu.slideDown(300);
                    
                    // Rotate arrow to down (smooth)
                    $arrow.addClass('rotated');
                }
            });
            
            console.log('✅ Smooth dropdown setup complete');
        }
        
        // ============================================
        // CURRENT PAGE DETECTION
        // ============================================
        
        function setCurrentPageDropdown() {
            const currentPath = window.location.pathname;
            console.log('🔍 Current path:', currentPath);
            
            $('.sidebar-wrapper .metismenu a').each(function() {
                const href = $(this).attr('href');
                
                if (href && href !== '#' && href !== 'javascript:;' && currentPath.includes(href)) {
                    const $link = $(this);
                    const $parentLi = $link.closest('li');
                    const $grandParentLi = $parentLi.closest('.metismenu > li');
                    
                    // If this is a submenu item
                    if ($grandParentLi.length && $grandParentLi[0] !== $parentLi[0]) {
                        const menuTitle = $grandParentLi.find('> a .menu-title').text();
                        console.log('🎯 Auto-opening dropdown for current page:', menuTitle);
                        
                        // Open parent dropdown without animation (instant)
                        $grandParentLi.addClass('mm-active mm-show');
                        $grandParentLi.find('> ul').show();
                        $grandParentLi.find('.dropdown-arrow i').addClass('rotated');
                        
                        // Mark current page
                        $parentLi.addClass('current-page');
                        $link.addClass('active');
                    }
                }
            });
        }
        
        // ============================================
        // MOBILE RESPONSIVE
        // ============================================
        
        function setupMobileToggle() {
            $('.mobile-toggle-menu').on('click', function() {
                // Close all dropdowns when mobile menu toggles
                setTimeout(function() {
                    $('.sidebar-wrapper .metismenu ul').slideUp(200);
                    $('.sidebar-wrapper .metismenu li').removeClass('mm-active mm-show');
                    $('.sidebar-wrapper .metismenu .dropdown-arrow i').removeClass('rotated');
                }, 300);
            });
        }
        
        // ============================================
        // INITIALIZATION SEQUENCE
        // ============================================
        
        // Initialize everything with proper timing
        setTimeout(function() {
            initSmoothDropdowns();
            setCurrentPageDropdown();
            setupMobileToggle();
            
            console.log('🎉 All dropdown animations ready!');
        }, 500);
        
        // ============================================
        // UTILITY FUNCTIONS
        // ============================================
        
        // Global function to manually close all dropdowns
        window.closeAllDropdowns = function() {
            $('.sidebar-wrapper .metismenu ul').slideUp(300);
            $('.sidebar-wrapper .metismenu li').removeClass('mm-active mm-show');
            $('.sidebar-wrapper .metismenu .dropdown-arrow i').removeClass('rotated');
            console.log('🔄 All dropdowns closed manually');
        };
        
        // Global function to open specific dropdown
        window.openDropdown = function(menuText) {
            $('.sidebar-wrapper .metismenu > li > a').each(function() {
                const title = $(this).find('.menu-title').text();
                if (title.toLowerCase().includes(menuText.toLowerCase())) {
                    $(this).trigger('click');
                    console.log('🎯 Opened dropdown:', title);
                    return false;
                }
            });
        };
        
        // Debug function
        window.debugDropdowns = function() {
            console.log('🔍 Debug Info:');
            console.log('Open dropdowns:', $('.sidebar-wrapper .metismenu li.mm-active').length);
            console.log('Rotated arrows:', $('.sidebar-wrapper .metismenu .dropdown-arrow i.rotated').length);
            
            $('.sidebar-wrapper .metismenu > li').each(function(index) {
                const title = $(this).find('.menu-title').text();
                const isOpen = $(this).hasClass('mm-active');
                const hasRotatedArrow = $(this).find('.dropdown-arrow i').hasClass('rotated');
                console.log(`${index + 1}. ${title} - Open: ${isOpen}, Arrow: ${hasRotatedArrow}`);
            });
        };
    });
    </script>
    
    @stack('scripts')
</body>
</html>