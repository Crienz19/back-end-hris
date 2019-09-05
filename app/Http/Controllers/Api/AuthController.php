<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['me', 'login', 'register']);    
    }

    public function register(Request $request) 
    {
        $request->validate([
            'email'     =>  'required|email',
            'name'      =>  'required',
            'password'  =>  'required'
        ]);

        $user = User::create([
            'email'     =>  $request->input('email'),
            'name'      =>  $request->input('name'),
            'password'  =>  bcrypt($request->input('password'))
        ]);

        return new UserResource($user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'name'      =>  'required',
            'password'  =>  'required'
        ]);

        if (!$token = auth()->attempt($request->only(['name', 'password']))) {
            return response()->json([
                'message'    =>  'Sorry we cant find you with those details.'
            ], 422);
        }

        return (new UserResource($request->user()))->additional([
            'meta'  =>  [
                'token' =>  $token
            ]
        ]);
    }

    public function me(Request $request) 
    {
        return new UserResource($request->user());
    }

    public function logout() 
    {
        auth()->logout();

        return response()->json([
            'message'   =>  'User logged out!'
        ], 200);
    }
}
