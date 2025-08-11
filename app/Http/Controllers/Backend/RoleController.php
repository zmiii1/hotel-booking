<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends BaseAdminController
{
    //  PERMISSIONS 
    public function AllPermission(){
        $this->checkAdminAccess(); // Manual role check
        
        $permissions = Permission::latest()->get();
        return view('backend.pages.permission.all_permission', compact('permissions'));
    }

    public function AddPermission(){
        return view('backend.pages.permission.add_permission');
    }

    public function StorePermission(Request $request){
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'group_name' => 'required'
        ]);

        Permission::create([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

        $notification = array(
            'message' => 'Permission Created Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.permission')->with($notification); 
    }

    public function EditPermission($id){
        $permission = Permission::findOrFail($id);
        return view('backend.pages.permission.edit_permission', compact('permission'));
    }

    public function UpdatePermission(Request $request){
        $permission = Permission::findOrFail($request->id);
        
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
            'group_name' => 'required'
        ]);

        $permission->update([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

        $notification = array(
            'message' => 'Permission Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.permission')->with($notification);
    }

    public function DeletePermission($id){
        Permission::findOrFail($id)->delete();
        
        $notification = array(
            'message' => 'Permission Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.permission')->with($notification);
    }

    //  ROLES 
    public function AllRoles(){
        $roles = Role::latest()->get();
        return view('backend.pages.roles.all_roles', compact('roles'));
    }

    public function AddRoles(){
        return view('backend.pages.roles.add_roles');
    }

    public function StoreRoles(Request $request){
        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);

        Role::create(['name' => $request->name]);

        $notification = array(
            'message' => 'Role Created Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.roles')->with($notification);
    }

    public function EditRoles($id){
        $role = Role::findOrFail($id);
        return view('backend.pages.roles.edit_roles', compact('role'));
    }

    public function UpdateRoles(Request $request){
        $role = Role::findOrFail($request->id);
        
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id
        ]);

        $role->update(['name' => $request->name]);

        $notification = array(
            'message' => 'Role Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.roles')->with($notification);
    }

    public function DeleteRoles($id){
        Role::findOrFail($id)->delete();
        
        $notification = array(
            'message' => 'Role Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.roles')->with($notification);
    }

    //  ROLE PERMISSIONS 
    public function AddRolesPermission(){
        $roles = Role::all();
        $permissions = Permission::all();
        $permission_groups = Permission::groupBy('group_name')->get(['group_name']);
        
        return view('backend.pages.rolesetup.add_roles_permission', 
            compact('roles', 'permissions', 'permission_groups'));
    }

    public function StoreRolesPermission(Request $request){
        $request->validate([
            'role_id' => 'required',
            'permission' => 'required|array'
        ]);

        $role = Role::findOrFail($request->role_id);
        $role->syncPermissions($request->permission);

        $notification = array(
            'message' => 'Role Permission Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.roles.permission')->with($notification);
    }

    public function AllRolesPermission(){
        $roles = Role::all();
        return view('backend.pages.rolesetup.all_roles_permission', compact('roles'));
    }

    public function EditRolesPermission($id){
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $permission_groups = Permission::groupBy('group_name')->get(['group_name']);
        
        return view('backend.pages.rolesetup.edit_roles_permission', 
            compact('role', 'permissions', 'permission_groups'));
    }

    public function UpdateRolesPermission(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $permissions = $request->permission ?? [];
        $role->syncPermissions($permissions); // Sekarang menerima nama permission
        
        $notification = array(
            'message' => 'Role Permission Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.roles.permission')->with($notification);
    }

    public function DeleteRolesPermission($id){
        $role = Role::findOrFail($id);
        $role->syncPermissions([]);

        $notification = array(
            'message' => 'Role Permission Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.roles.permission')->with($notification);
    }
}