<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission; 
use DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Helper method to get permissions by group name
     */
    public static function getpermissionGroups(){

        $permission_groups = DB::table('permissions')->select('group_name')->groupBy('group_name')->get();
        return $permission_groups;

    } // End Method 

    public static function getpermissionByGroupName($group_name){

        $permissions = DB::table('permissions')
                            ->select('name','id')
                            ->where('group_name',$group_name)
                            ->get();
            return $permissions;

    }// End Method 

    public static function roleHasPermissions($role,$permissions){
        $hasPermission = true;
        foreach ($permissions as $permission) {
           if (!$role->hasPermissionTo($permission->name)) {
            $hasPermission = false;
           }
           return $hasPermission;
        }
    }// End Method

    /**
     * Check if user is any type of admin
     */
    public function isAdmin()
    {
        return $this->hasAnyRole(['Super Admin', 'Admin', 'Receptionist', 'Cashier']);
    }

    /**
     * Check if user is receptionist
     */
    public function isReceptionist()
    {
        return $this->hasRole('Receptionist');
    }

    /**
     * Check if user is cashier
     */
    public function isCashier()
    {
        return $this->hasRole('Cashier');
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('Super Admin');
    }
}

