<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Backend\StaffController;
use App\Http\Controllers\Backend\RoomTypeController;
use App\Http\Controllers\Backend\RoomController;
use App\Http\Controllers\Backend\PromoCodeController;
use App\Http\Controllers\Backend\RoomPackageController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\AdminManagementController; 
use App\Http\Controllers\Frontend\FrontendRoomController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Backend\RoomAddOnsController;
use App\Http\Controllers\Backend\BookingManagementController;
use App\Http\Controllers\XenditPaymentController;
use App\Http\Controllers\Frontend\BeachTicketController;
use App\Http\Controllers\Frontend\TicketOrderController;
use App\Http\Controllers\Backend\POSController;
use App\Http\Controllers\Frontend\BeachTicketController as FrontendBeachTicketController;
use App\Http\Controllers\Backend\BeachTicketController as BackendBeachTicketController;
use App\Http\Controllers\Frontend\TicketOrderController as FrontendTicketOrderController;
use App\Http\Controllers\Backend\TicketOrderController as BackendTicketOrderController;
use App\Http\Controllers\Frontend\BeachPromoCodeController as FrontendBeachPromoCodeController;
use App\Http\Controllers\Backend\BeachPromoCodeController as BackendBeachPromoCodeController;

//  PUBLIC ROUTES 
Route::get('/', [UserController::class, 'Index']);

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified']], function(){
    Route::get('/admin', [AdminController::class, 'AdminIndex'])->name('admin.index');
    Route::get('/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
    Route::get('/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
    Route::get('/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
    Route::post('/password/update', [AdminController::class, 'AdminPasswordUpdate'])->name('admin.password.update');

    
});

//  AUTH ROUTES 
Route::get('/admin/login', [AdminController::class, 'AdminLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'processLogin'])->name('admin.process.login');


//  USER DASHBOARD (Keep for regular users) 
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//  ADMIN GROUP MIDDLEWARE (Updated with role-based access) 
Route::middleware(['auth', 'role:Super Admin|Admin|Receptionist|Cashier'])->group(function(){
    
    // Admin Dashboard & Profile Routes (All admin roles can access)
    Route::controller(AdminController::class)->group(function() {
        Route::get('/admin/dashboard', 'AdminDashboard')->name('admin.dashboard');
        Route::get('/admin/profile', 'AdminProfile')->name('admin.profile');
        Route::post('/admin/profile/store', 'AdminProfileStore')->name('admin.profile.store');
        Route::get('/admin/change/password', 'AdminChangePassword')->name('admin.change.password');
        Route::post('/admin/password/update', 'AdminPasswordUpdate')->name('admin.password.update');
    });

    //  BOOKING MANAGEMENT (Admin & Receptionist) 
    Route::middleware(['auth'])->group(function() {
        Route::controller(BookingManagementController::class)->group(function() {
            // PINDAHKAN EXPORT ROUTES KE ATAS (sebelum route dengan parameter)
            Route::get('/admin/bookings/export-options', 'showExportOptions')->name('admin.bookings.export.options');
            Route::get('/admin/bookings/export', 'export')->name('admin.bookings.export');
            Route::get('/admin/bookings/dashboard/{id}', 'dashboard')->name('admin.bookings.dashboard');
            // Route dengan parameter di bawah
            Route::get('/admin/bookings', 'index')->name('admin.bookings');
            Route::get('/admin/bookings/{id}', 'show')->name('admin.bookings.show');

            Route::delete('/admin/bookings/{id}', 'destroy')->name('admin.bookings.destroy');
        });
    });

    // Tambahkan route yang hilang
    Route::middleware(['auth', 'role:Super Admin|Admin|Receptionist|Cashier'])->group(function(){
        Route::middleware(['permission:booking.edit'])->group(function() {
            Route::post('/admin/bookings/{id}/status', [BookingManagementController::class, 'updateStatus'])->name('admin.bookings.status');
        });
    });
    //  POS ROUTES (Cashier & Admin) 
    Route::middleware(['permission:pos.access'])->group(function() {
        Route::prefix('backend/pos')->name('backend.pos.')->group(function () {
            Route::get('/dashboard', [POSController::class, 'dashboard'])->name('dashboard');
            Route::get('/', [POSController::class, 'index'])->name('index');
            Route::post('/process', [POSController::class, 'processOrder'])->name('process');
            Route::get('/print-receipt/{order_code}', [POSController::class, 'printReceipt'])->name('print-receipt');
            Route::post('/apply-promo', [POSController::class, 'applyPromoCode'])->name('apply-promo');
        });
    });

    //  ROOM MANAGEMENT (Admin only) 
    Route::middleware(['permission:dashboard.view'])->group(function() {
        
        // Room Types
        Route::controller(RoomTypeController::class)->group(function(){
            Route::get('/tlroom/list/', 'TlRoomList')->name('tlroom.list');
            Route::get('/kalicaaroom/list/', 'KalicaaRoomList')->name('kalicaaroom.list');
            Route::get('/lbvroom/list/', 'LbvRoomList')->name('lbvroom.list');
            Route::get('/lalassaroom/list/', 'LalassaRoomList')->name('lalassaroom.list');
            Route::get('/add/roomtype/{id}', 'AddRoomType')->name('add.room.type');
            Route::post('/store/roomtype/{id}', 'StoreRoomType')->name('store.room.type');
            Route::delete('/delete/roomtype/{id}', 'DeleteRoomType')->name('delete.room.type');
        });

        // Rooms
        Route::controller(RoomController::class)->group(function(){
            Route::get('/edit/room/{id}', 'EditRoom')->name('edit.room');
            Route::post('/update/room/{id}', 'UpdateRoom')->name('update.room');
            Route::post('/store/room/num/{id}', 'StoreRoomNumber')->name('store.room.num');
            Route::get('/edit/roomnum/{id}', 'EditRoomNumber')->name('edit.roomnum');
            Route::post('/update/roomnum/{id}', 'UpdateRoomNumber')->name('update.roomnum');
            Route::get('/delete/roomnum/{id}', 'DeleteRoomNumber')->name('delete.roomnum');
            Route::delete('/delete/gallery/image/{id}', 'deleteGalleryImage')->name('delete.gallery.image');
        });

        // Promo Codes
        Route::controller(PromoCodeController::class)->group(function() {
            Route::get('/promo-codes', 'index')->name('promo.codes');
            Route::get('/promo-codes/create', 'create')->name('promo.codes.create');
            Route::post('/promo-codes/store', 'store')->name('promo.codes.store');
            Route::get('/promo-codes/edit/{id}', 'edit')->name('promo.codes.edit');
            Route::post('/promo-codes/update/{id}', 'update')->name('promo.codes.update');
            Route::get('/promo-codes/delete/{id}', 'destroy')->name('promo.codes.delete');
        });

        // Room Packages
        Route::controller(RoomPackageController::class)->group(function() {
            Route::get('/admin/room-packages', 'index')->name('room.packages');
            Route::get('/admin/room-packages/create', 'create')->name('room.packages.create');
            Route::post('/admin/room-packages/store', 'store')->name('room.packages.store');
            Route::get('/admin/room-packages/edit/{id}', 'edit')->name('room.packages.edit');
            Route::post('/admin/room-packages/update/{id}', 'update')->name('room.packages.update');
            Route::get('/admin/room-packages/delete/{id}', 'destroy')->name('room.packages.delete');
        });

        // Room Add-ons
        Route::controller(RoomAddOnsController::class)->group(function() {
            Route::get('/admin/room-addons', 'index')->name('room-addons.index');
            Route::get('/admin/room-addons/create', 'create')->name('room-addons.create');
            Route::post('/admin/room-addons', 'store')->name('room-addons.store');
            Route::get('/admin/room-addons/{roomAddon}', 'show')->name('room-addons.show');
            Route::get('/admin/room-addons/{roomAddon}/edit', 'edit')->name('room-addons.edit');
            Route::put('/admin/room-addons/{roomAddon}', 'update')->name('room-addons.update');
            Route::delete('/admin/room-addons/{roomAddon}', 'destroy')->name('room-addons.destroy');
        });
    });

    // Beach ticket (for dashboard) & Ticket order ===> (Backend controller)
    Route::middleware(['permission:pos.access'])->group(function() {
        Route::prefix('backend/beach-tickets')->name('backend.beach-tickets.')->group(function () {
            
            // DASHBOARD - In BeachTicketController
            Route::get('/dashboard', [BackendBeachTicketController::class, 'dashboard'])->name('dashboard');
            
            // ORDERS MANAGEMENT - TicketOrderController (NO POS methods)
            Route::prefix('orders')->name('orders.')->group(function () {
                Route::get('/', [BackendTicketOrderController::class, 'index'])->name('index');
                Route::get('/create', [BackendTicketOrderController::class, 'create'])->name('create');
                Route::post('/', [BackendTicketOrderController::class, 'store'])->name('store');
                Route::get('/{order_code}', [BackendTicketOrderController::class, 'show'])->name('show');
                Route::post('/{id}/mark-as-paid', [BackendTicketOrderController::class, 'markAsPaid'])->name('mark-as-paid');
                Route::get('/receipt/{order_code}', [BackendTicketOrderController::class, 'printReceipt'])->name('print-receipt');
                Route::delete('/{id}', [BackendTicketOrderController::class, 'destroy'])->name('destroy');
                
                // EXPORT - In BeachTicketController
                Route::get('/backend/beach-tickets/export-form', [BackendBeachTicketController::class, 'showExportForm'])->name('export');
                Route::get('/backend/beach-tickets/export-download', [BackendBeachTicketController::class, 'export'])->name('export.download');
                Route::get('/backend/beach-tickets/preview-data', [BackendBeachTicketController::class, 'previewData'])->name('preview-data');
                Route::get('/backend/beach-tickets/export-stats', [BackendBeachTicketController::class, 'getQuickStats'])->name('export.stats');
            });
            
            // POS SYSTEM - POSController (ALL POS operations)
            Route::prefix('pos')->name('pos.')->group(function () {
                Route::get('/dashboard', [POSController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [POSController::class, 'index'])->name('index');
                Route::post('/process', [POSController::class, 'processOrder'])->name('process');
                
                // PASTIKAN ROUTE INI ADA DAN BENAR:
                Route::post('/apply-promo', [POSController::class, 'applyPromo'])->name('apply-promo');
                
                Route::get('/receipt/{order_code}', [POSController::class, 'printReceipt'])->name('receipt');
            });
        });
    });

    // BeachTicket & BeachPromoCode (Backend Controller)
    Route::middleware(['permission:dashboard.view'])->group(function() {
        Route::prefix('backend/beach-tickets/manage')->name('backend.beach-tickets.manage.')->group(function () {
            Route::get('/', [BackendBeachTicketController::class, 'index'])->name('index');
            Route::get('/create', [BackendBeachTicketController::class, 'create'])->name('create');
            Route::post('/', [BackendBeachTicketController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [BackendBeachTicketController::class, 'edit'])->name('edit');
            Route::put('/{id}', [BackendBeachTicketController::class, 'update'])->name('update');
            Route::delete('/{id}', [BackendBeachTicketController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('backend/beach-tickets/promo-codes')->name('backend.beach-tickets.promo-codes.')->group(function () {
            Route::get('/', [App\Http\Controllers\Backend\BeachPromoCodeController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Backend\BeachPromoCodeController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Backend\BeachPromoCodeController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [App\Http\Controllers\Backend\BeachPromoCodeController::class, 'edit'])->name('edit');
            Route::put('/{id}', [App\Http\Controllers\Backend\BeachPromoCodeController::class, 'update'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\Backend\BeachPromoCodeController::class, 'destroy'])->name('destroy');
        });
    });
});

//  ROLE & PERMISSION MANAGEMENT (Super Admin & Admin) 
Route::middleware(['auth'])->group(function() {
    
    // Permission Routes
    Route::controller(RoleController::class)->group(function(){
        Route::get('/all/permission', 'AllPermission')->name('all.permission');
        Route::get('/add/permission', 'AddPermission')->name('add.permission');
        Route::post('/store/permission', 'StorePermission')->name('store.permission');
        Route::get('/edit/permission/{id}', 'EditPermission')->name('edit.permission');
        Route::post('/update/permission', 'UpdatePermission')->name('update.permission');
        Route::get('/delete/permission/{id}', 'DeletePermission')->name('delete.permission');

        // Role Routes
        Route::get('/all/roles', 'AllRoles')->name('all.roles');
        Route::get('/add/roles', 'AddRoles')->name('add.roles');
        Route::post('/store/roles', 'StoreRoles')->name('store.roles');
        Route::get('/edit/roles/{id}', 'EditRoles')->name('edit.roles');
        Route::post('/update/roles', 'UpdateRoles')->name('update.roles');
        Route::get('/delete/roles/{id}', 'DeleteRoles')->name('delete.roles');

        // Role Permission Routes (ROLE SETUP)
        Route::get('/add/roles/permission', 'AddRolesPermission')->name('add.roles.permission');
        Route::post('/store/roles/permission', 'StoreRolesPermission')->name('store.roles.permission');
        Route::get('/all/roles/permission', 'AllRolesPermission')->name('all.roles.permission');
        Route::get('/edit/roles/permission/{id}', 'EditRolesPermission')->name('edit.roles.permission');
        Route::post('/update/roles/permission/{id}', 'UpdateRolesPermission')->name('update.roles.permission'); // TAMBAH {id}
        Route::get('/delete/roles/permission/{id}', 'DeleteRolesPermission')->name('delete.roles.permission');
        
        // TAMBAHKAN ROUTE YANG HILANG UNTUK ADMIN ROLES UPDATE
        Route::post('/admin/roles/update/{id}', 'UpdateRoles')->name('admin.roles.update');
    });
});

//  ADMIN MANAGEMENT (Super Admin Only) 
Route::middleware(['auth', 'role:Super Admin'])->group(function() {
    Route::controller(AdminManagementController::class)->group(function(){
        Route::get('/all/admin', 'AllAdmin')->name('all.admin');
        Route::get('/add/admin', 'AddAdmin')->name('add.admin');
        Route::post('/store/admin', 'StoreAdmin')->name('store.admin');
        Route::get('/admin/show/credentials', 'showCredentials')->name('admin.show.credentials');
        Route::get('/edit/admin/{id}', 'EditAdmin')->name('edit.admin');
        Route::post('/update/admin', 'UpdateAdmin')->name('update.admin');
        Route::get('/delete/admin/{id}', 'DeleteAdmin')->name('delete.admin');
        Route::get('/reset/password/{id}', 'ResetPassword')->name('reset.password');
    });
});

//  FRONTEND ROUTES (Keep as is) 
Route::controller(FrontendRoomController::class)->group(function(){
    Route::get('room/reservation/', 'RoomReservation')->name('room.reservation');
    Route::get('/room/package',  'RoomPackage')->name('room.package');
    Route::get('check/availability/', 'CheckAvailability')->name('check.availability');
    Route::get('hotel/{hotelSlug}/rooms', 'HotelRoomReservation')->name('hotel.rooms');
});

// Booking Process Routes (Frontend)
Route::controller(BookingController::class)->group(function() {
    Route::get('room/addons/', 'roomAddons')->name('room.addons');
    Route::post('/room/addon/add', 'addAddon')->name('room.addon.add');
    Route::post('/room/addon/update', 'updateAddon')->name('room.addon.update');
    
    // Booking details form
    Route::get('/booking/details', 'bookingDetails')->name('booking.details');
    Route::post('/booking/create', 'createBooking')->name('booking.create');
    
    // Payment processing
    Route::get('/booking/payment/{code}', 'showPaymentOptions')->name('booking.payment');
    Route::post('/booking/payment/{code}', 'processPayment')->name('booking.process.payment');
    
    // Booking confirmation
    Route::get('/booking/confirmation/{code}', 'showConfirmation')->name('booking.confirmation');
    Route::get('/booking/{code}/manual-transfer',  'showManualTransfer')->name('booking.manual_transfer');
    Route::post('/booking/{code}/upload-receipt', 'uploadReceipt')->name('booking.upload_receipt');
    Route::get('/booking/print-summary', 'printSummary')->name('booking.print.summary');
});

// User Booking Management (requires authentication)
Route::middleware(['auth'])->controller(BookingController::class)->group(function() {
    Route::get('/user/bookings', 'userBookings')->name('user.bookings');
    Route::get('/user/bookings/{code}', 'userBookingDetails')->name('user.booking.details');
    Route::post('/user/bookings/{code}/cancel', 'cancelBooking')->name('user.booking.cancel');
});

// Xendit Payment Routes
Route::get('/booking/{code}/payment/xendit', [XenditPaymentController::class, 'showPayment'])->name('booking.payment.xendit');
Route::get('/payment/success/{code}', [XenditPaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment/failed/{code}', [XenditPaymentController::class, 'paymentFailed'])->name('payment.failed');
Route::post('/payment/xendit/webhook', [XenditPaymentController::class, 'webhook'])->name('xendit.webhook');
Route::post('/payment/manual-update/{code}', [XenditPaymentController::class, 'manualUpdate'])->name('payment.manual.update');

// Beach Tickets Routes (Frontend)
Route::controller(BeachTicketController::class)->group(function() {
    Route::get('/beach-tickets',  'index')->name('beach-tickets.index');
    Route::get('/beach-tickets/{id}',  'show')->name('beach-tickets.show');
    Route::post('/beach-tickets/checkout',  'checkout')->name('beach-tickets.checkout');
});

Route::controller(FrontendBeachPromoCodeController::class)->group(function(){
    Route::post('/beach-tickets/apply-promo',  'applyPromo')->name('beach-tickets.apply-promo');
    Route::post('/beach-tickets/remove-promo',  'removePromo')->name('beach-tickets.remove-promo');
});

// Ticket Orders Routes (Frontend)
Route::controller(TicketOrderController::class)->group(function(){
    Route::post('/ticket-orders',  'store')->name('ticket-orders.store');
    Route::get('/ticket-orders/payment/{order_code}',  'showPayment')->name('ticket-orders.payment');
    Route::get('/ticket-orders/payment/success/{order_code}',  'paymentSuccess')->name('ticket-orders.payment.success');
    Route::get('/ticket-orders/payment/failed/{order_code}',  'paymentFailed')->name('ticket-orders.payment.failed');
    Route::get('/ticket-orders/confirmation/{order_code}',  'showConfirmation')->name('ticket-orders.confirmation');
    Route::post('/ticket-orders/manual-update/{order_code}',  'manualUpdate')->name('ticket-orders.manual-update');
    Route::post('/ticket-orders/webhook',  'webhook')->name('ticket-orders.webhook');
});

// Navbar route (frontend)
Route::controller(UserController::class)->group(function() {
    Route::get('/', 'HomePage')->name('home');
    Route::get('/tanjung-lesung-beach-hotel', 'TanjungLesungBeachHotel')->name('tanjung.lesung');
    Route::get('/kalicaa-villa', 'KalicaaVilla')->name('kalicaa.villa');
    Route::get('/ladda-bay-village', 'LaddaBayVillage')->name('ladda.bay');
    Route::get('/lalassa-beach-club', 'LalassaBeachClub')->name('lalassa.beach');
    Route::get('/mice', 'Mice')->name('mice');
    Route::get('/activities', 'Activities')->name('activities');
    Route::get('/contact', 'ContactUs')->name('contact.us');
    Route::post('/contact/submit', 'submitContactForm')->name('contact.submit');
});

