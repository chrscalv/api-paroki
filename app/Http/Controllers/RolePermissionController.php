<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Auth;

class RolePermissionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if($user->hasPermissionTo('see all post')){
            return response()->json(Role::all(), 200);
            // return response()->json(Role::all(), 200);
        }else{
            return response()->json([
                'error'=> 'abort'
            ]);
        }
    }
}
