<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all(),200);
    }

    public function show($user)
    {
        return response()->json($user, 200);
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
                'error' => $validator->error
            ]);
        }

        $status = 401;
        $response = ['error'=>'Unauthorised'];

        if(Auth::attemt($request->only(['email', 'password']))){
            $status = 200;
            return response()->json([
                'token'  => Auth::user(),
                'tokens' => Auth::user()->createToken('blog')->accessToken()
            ]);
            return response()->json($status,$response);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'      => 'required|unique:users|max:255',
            'email'         => 'required|email',
            'password'      => 'required|min:6|',
            'c_password'    => 'required|same:password'
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->error
            ]);
        }

        $data = $request->only('email','username');
        $data['password'] = becrypt($data['password']);
        $user = User::create($data);
        $user->assignRole($request->role);

        return response()->json([
            'user'  => $user
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'     => 'required|email',
            'username'  => 'required|max:255'
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->error
            ]);
        }
    }

    public function destroy(User $user)
    {
        $status     = $user->delete();

        return response()->json([
            'status'    => (bool)$user,
            'message'   => $user ? 'User Deleted' : 'Error Deleting User'
        ]);
    }
}
