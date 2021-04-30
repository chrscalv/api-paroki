<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Validator;
use Auth;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if($user->hasPermissionTo('see all user')){
            return response()->json(User::with('roles')->get(),200);
        }{
            return response()->json([
                'response'=> 401,
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    public function me(){
        $user = Auth::user();
        $role = $user->getRoleNames();
        return response()->json([
            'data' => [
                'user'  => $user,
                'role'  => $role
            ]
        ]);
    }

    public function show($user)
    {
        $user = Auth::user();
        if($user->hasPermissionTo('see user profile')){
            return response()->json($user, 200);
        }else{
            return response()->json([
                'response'  => 401,
                'message'   => 'Unauthorized'
            ], 401);
        }
    }

    public function profile()
    {
        $user = Auth::user();
        return response()->json($user, 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required',
            'password'  => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()
            ]);
        }

        $status = 401;
        $response = ['error'=>'jancuk'];

        if (Auth::attempt($request->only(['email', 'password']))) {
            $status = 200;
            $response = [
                'user' => Auth::user(),
                'token' => Auth::user()->createToken('blog')->accessToken,
            ];
        }

        return response()->json($response, $status);
    }

    public function register(Request $request)
    {
        $user = Auth::user();
        if($user->hasPermissionTo('register user')){
            $validator = Validator::make($request->all(), [
                'name'      => 'required|unique:users|max:255',
                'email'         => 'required|email',
                'password'      => 'required|min:6|',
                'c_password'    => 'required|same:password'
            ]);
    
            if($validator->fails()){
                return response()->json([
                    'error' => $validator->errors()
                ]);
            }
    
            $data = $request->only('email','name', 'password');
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);
            $user->assignRole($request->role);
    
            return response()->json([
                'user'  => $user
            ]);
        }else{
            return response()->json([
                'response'  => 401,
                'message'   => 'Unauthorized'
            ],401);
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if($user->hasPermissionTo('update user')){
            $validator = Validator::make($request->all(),[
                'email'     => 'required|email',
                'username'  => 'required|max:255'
            ]);
    
            if($validator->fails()){
                return response()->json([
                    'error' => $validator->error
                ]);
            }
        }else{
            return response()->json([
                'response'  => 401,
                'message'   => ''
            ]);
        }
    }

    public function destroy(User $user)
    {
        $user = Auth::user();
        $status     = $user->delete();

        return response()->json([
            'status'    => (bool)$user,
            'message'   => $user ? 'User Deleted' : 'Error Deleting User'
        ]);
    }
}
