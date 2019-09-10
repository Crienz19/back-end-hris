<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserResourceWithEmployeeDetails;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        switch (auth()->user()->role) {
            case 'superadministrator':
                $users = User::where('role', '!=', 'superadministrator')
                             ->where('role', '!=', 'hr')
                             ->where('role', '!=', 'administrator')
                             ->get();
                break;
            case 'hr':
                $users = User::where('role', '!=', 'superadministrator')
                    ->where('role', '!=', 'hr')
                    ->get();
                break;
            case 'administrator':
                $users = User::where('role', '!=', 'superadministrator')
                            ->where('role', '!=', 'hr')
                            ->where('role', '!=', 'administrator')
                            ->get();
                break;
            default:
                $users = User::where('role', '!=', 'superadministrator')
                    ->where('role', '!=', 'hr')
                    ->get();
                break;
        }

        return UserResourceWithEmployeeDetails::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $storedUser = User::create($request->all());

        return new UserResource($storedUser);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $selectedUser = User::find($id);

        return new UserResourceWithEmployeeDetails($selectedUser);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        User::find($id)->update($request->all());


        return response()->json([
            'message'   =>  'User Updated!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deletedUser = User::find($id)->delete();

        return response()->json([
            'message'   =>  'User Deleted'
        ], 200);
    }
}
