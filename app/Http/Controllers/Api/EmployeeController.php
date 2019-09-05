<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employee;
use App\Http\Resources\Employee\EmployeeResource;
use App\Http\Resources\Employee\EmployeeResourceWithCompleteDetails;

class EmployeeController extends Controller
{
    private $user;
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
        $employees = Employee::all();

        return EmployeeResource::collection($employees);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        User::find(auth()->user()->id)->update([
            'isFilled'  =>  1
        ]);

        $storedEmployee = Employee::create($request->all());

        return new EmployeeResource($storedEmployee);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $selectedEmployee = Employee::where(['user_id' => $id])->first();

        return new EmployeeResourceWithCompleteDetails($selectedEmployee);
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
        $updateEmployee = Employee::where('user_id', $id)->update($request->all());

        return new EmployeeResource($updateEmployee);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deletedEmployee = Employee::find($id)->delete();

        return new EmployeeResource($deletedEmployee);
    }
}
