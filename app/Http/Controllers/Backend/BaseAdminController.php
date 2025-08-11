<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BaseAdminController extends Controller
{
    protected function checkSuperAdmin()
    {
        $user = Auth::user();
        
        if (!$user || !$user->hasRole('Super Admin')) {
            abort(403, 'Super Admin access required');
        }
    }

    protected function checkAdminAccess()
    {
        $user = Auth::user();
        
        if (!$user || !$user->hasAnyRole(['Super Admin', 'Admin'])) {
            abort(403, 'Admin access required');
        }
    }
}
