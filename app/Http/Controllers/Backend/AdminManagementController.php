<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Str;

class AdminManagementController extends BaseAdminController
{
    public function AllAdmin(){
        $this->checkSuperAdmin(); // Manual role check
        
        $admins = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['Super Admin', 'Admin', 'Receptionist', 'Cashier']);
        })->latest()->get();
        
        return view('backend.pages.admin.all_admin', compact('admins'));
    }

    public function AddAdmin(){
        $roles = Role::all();
        return view('backend.pages.admin.add_admin', compact('roles'));
    }

    public function StoreAdmin(Request $request){
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'username' => 'required|unique:users',
        'role_name' => 'required'
    ]);

    $password = Str::random(12);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'username' => $request->username,
        'password' => Hash::make($password),
        'role' => 'admin',
        'status' => 'active',
    ]);

    $user->assignRole($request->role_name);

    // REDIRECT KE HALAMAN UNIVERSAL CREDENTIALS
    return redirect()->route('admin.show.credentials')
        ->with('credentials_data', [
            'action' => 'created',
            'admin' => [
                'id' => $user->id,
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'role' => $request->role_name
            ],
            'password' => $password
        ]);
}

public function ResetPassword($id){
    $admin = User::findOrFail($id);
    $newPassword = Str::random(12);
    
    $admin->update([
        'password' => Hash::make($newPassword)
    ]);

    // REDIRECT KE HALAMAN UNIVERSAL CREDENTIALS
    return redirect()->route('admin.show.credentials')
        ->with('credentials_data', [
            'action' => 'reset',
            'admin' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'username' => $admin->username,
                'email' => $admin->email,
                'role' => $admin->getRoleNames()->first()
            ],
            'password' => $newPassword
        ]);
}

// METHOD BARU - UNIVERSAL UNTUK SHOW CREDENTIALS
public function showCredentials()
{
    $credentialsData = session('credentials_data');
    
    if (!$credentialsData) {
        return redirect()->route('all.admin')->with('error', 'No credentials data found');
    }
    
    return view('backend.pages.admin.show_credentials', compact('credentialsData'));
}

    public function EditAdmin($id){
        $admin = User::findOrFail($id);
        $roles = Role::all();
        return view('backend.pages.admin.edit_admin', compact('admin', 'roles'));
    }

    public function UpdateAdmin(Request $request){
        $admin = User::findOrFail($request->id);
        
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'username' => 'required|unique:users,username,' . $admin->id,
            'role_name' => 'required'
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
        ]);

        $admin->syncRoles([$request->role_name]);

        $notification = array(
            'message' => 'Admin Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.admin')->with($notification);
    }

    public function DeleteAdmin($id){
        $admin = User::findOrFail($id);
        

        /** @var \App\Models\User|null $currentUser */
        $currentUser = Auth::user();
        
        if($admin->hasRole('Super Admin') || ($currentUser && $admin->id === $currentUser->id)){
            $notification = array(
                'message' => 'Cannot delete this admin!',
                'alert-type' => 'error'
            );
            return redirect()->route('all.admin')->with($notification);
        }

        $admin->delete();
        
        $notification = array(
            'message' => 'Admin Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.admin')->with($notification);
    }

}

?>

