<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function AdminDashboard(){
        try {
            // Get actual booking statistics from database
            $totalBookings = Booking::count();
            $confirmedBookings = Booking::where('status', 'confirmed')->count();
            $pendingBookings = Booking::where('status', 'pending')->count();
            
            // Calculate total revenue from paid bookings
            $totalRevenue = Booking::where('payment_status', 'paid')->sum('total_amount') ?? 0;
            
            // Get recent bookings
            $recentBookings = Booking::with(['room', 'hotel', 'roomType'])
                ->latest()
                ->take(5)
                ->get();
            
            // Get bookings by hotel
            $bookingsByHotel = Booking::selectRaw('hotel_id, count(*) as total')
                ->groupBy('hotel_id')
                ->with('hotel')
                ->get()
                ->map(function($item) {
                    return [
                        'hotel_name' => $item->hotel->name ?? 'Unknown',
                        'total' => $item->total
                    ];
                });
            
            // Get monthly bookings for current year
            $currentYear = Carbon::now()->year;
            $monthlyBookings = Booking::selectRaw('MONTH(created_at) as month, count(*) as total_bookings, sum(total_amount) as total_revenue')
                ->whereYear('created_at', $currentYear)
                ->groupBy('month')
                ->get();
            
            // Prepare chart data
            $chartLabels = [];
            $chartBookingData = [];
            $chartRevenueData = [];
            
            for ($i = 1; $i <= 12; $i++) {
                $monthName = Carbon::create()->month($i)->format('M');
                $chartLabels[] = $monthName;
                
                $monthData = $monthlyBookings->firstWhere('month', $i);
                $chartBookingData[] = $monthData ? $monthData->total_bookings : 0;
                $chartRevenueData[] = $monthData ? $monthData->total_revenue : 0;
            }

            return view('admin.index', compact(
                'totalBookings', 'confirmedBookings', 'pendingBookings',
                'totalRevenue', 'recentBookings', 'bookingsByHotel',
                'chartLabels', 'chartBookingData', 'chartRevenueData'
            ));
            
        } catch (\Exception $e) {
            // Fallback with error logging
            \Log::error('Admin Dashboard Error: ' . $e->getMessage());
            
            // Return with basic data if database query fails
            $totalBookings = 0;
            $confirmedBookings = 0;
            $pendingBookings = 0;
            $totalRevenue = 0;
            $recentBookings = collect();
            $bookingsByHotel = collect();
            $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $chartBookingData = array_fill(0, 12, 0);
            $chartRevenueData = array_fill(0, 12, 0);

            return view('admin.index', compact(
                'totalBookings', 'confirmedBookings', 'pendingBookings',
                'totalRevenue', 'recentBookings', 'bookingsByHotel',
                'chartLabels', 'chartBookingData', 'chartRevenueData'
            ))->with('error', 'Dashboard data could not be loaded completely.');
        }
    }

    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function AdminLogin(){
        return view('admin.admin_login');
    }

    public function processLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            if ($user && $user->hasAnyRole(['Super Admin', 'Admin', 'Receptionist', 'Cashier'])) {
                $notification = array(
                    'message' => 'Login Successfully',
                    'alert-type' => 'success'
                );
                // Fixed: Use admin.dashboard instead of admin.index
                return redirect()->intended(route('admin.dashboard'))->with($notification);
            } else {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'You do not have permission to access admin area.',
                ]);
            }
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    public function AdminProfile(){
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_profile_view', compact('profileData'));
    }

    public function AdminProfileStore(Request $request){
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;

        if($request->file('photo')){
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/'.$data->photo));

            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'),$filename);
            $data->photo = $filename;
        }
        $data->save();

        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function AdminChangePassword(){
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_change_password', compact('profileData'));
    }

    public function AdminPasswordUpdate(Request $request){
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        if(!Hash::check($request->old_password, Auth::user()->password)){
            $notification = array(
                'message' => 'Old Password Does not Match!',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }

        User::whereId(Auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $notification = array(
            'message' => 'Password Change Successfully',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }
}