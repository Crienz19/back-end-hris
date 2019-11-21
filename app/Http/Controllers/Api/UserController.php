<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserResourceWithEmployeeDetails;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index()
    {
        $users = User::orderBy('name', 'asc')
            ->get()
            ->map
            ->format();

        return response()->json($users);
    }


    public function show($id)
    {
        $selectedUser = User::find($id);

        return new UserResourceWithEmployeeDetails($selectedUser);
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id', $id);
        $user->update($request->all());

        return response()->json($user->first()->format());
    }

    public function destroy($id)
    {
        $deletedUser = User::find($id)->delete();

        return response()->json([
            'message'   =>  'User Deleted'
        ], 200);
    }

    public function changePassword(Request $request)
    {
        if (Auth::attempt(['name' => auth()->user()->name, 'password' => $request->input('current_password')])) {

            User::find(auth()->user()->id)->update([
                'password'  =>  bcrypt($request->input('new_password'))
            ]);

            return response()->json([
                'message'   =>  'Password Changed'
            ], 200);
        } else {
            return response()->json([
                'message'   =>  'Incorrect Password'
            ], 401);
        }
    }

    public function setToDefault($id) 
    {
        User::find($id)->update([
            'password'  =>  bcrypt('z1ptr4v3l')
        ]);

        return response()->json([
            'message'   =>  'Password set to default.'
        ]);
    }
}
