<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Leave\LeaveResourceWithEmployeeAndActions;
use App\Http\Resources\Leave\LeaveResourceWithEmployeeDetails;
use App\Leave;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        switch (auth()->user()->role) {
            case 'superadministrator':
                $leaves = Leave::all();
                return LeaveResourceWithEmployeeDetails::collection($leaves);
            break;

            case 'hr':
                $leaves = Leave::where('recommending_approval', '=', 'Approved')
                    ->get();
                return LeaveResourceWithEmployeeAndActions::collection($leaves);
                break;

            case 'supervisor':
                $leaves = Leave::join('users', 'users.id', '=', 'leaves.user_id')
                    ->join('employees', 'leaves.user_id', '=', 'employees.user_id')
                    ->join('departments', 'employees.department_id', '=', 'departments.id')
                    ->select('leaves.*')
                    ->where('users.role', '=', 'employee')
                    ->get();

                return LeaveResourceWithEmployeeDetails::collection($leaves);
                break;

            case 'employee':

                break;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
