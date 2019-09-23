<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employee;
use App\Http\Resources\Employee\EmployeeResource;
use App\Http\Resources\Employee\EmployeeResourceWithCompleteDetails;
use Illuminate\Support\Facades\File;

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
        $updateEmployee = Employee::where('user_id', $id)->update([
            'first_name'        =>  $request->input('first_name'),
            'middle_name'       =>  $request->input('middle_name'),
            'last_name'         =>  $request->input('last_name'),
            'birth_date'        =>  $request->input('birth_date'),
            'civil_status'      =>  $request->input('civil_status'),
            'contact_no_1'      =>  $request->input('contact_no_1'),
            'contact_no_2'      =>  $request->input('contact_no_2'),
            'present_address'   =>  $request->input('present_address'),
            'permanent_address' =>  $request->input('permanent_address'),
            'sss'               =>  $request->input('sss'),
            'pagibig'           =>  $request->input('pagibig'),
            'philhealth'        =>  $request->input('philhealth'),
            'tin'               =>  $request->input('tin'),
            'employee_id'       =>  $request->input('employee_id'),
            'date_hired'        =>  $request->input('date_hired'),
            'branch_id'         =>  $request->input('branch_id'),
            'skype_id'          =>  $request->input('skype_id'),
            'department_id'     =>  $request->input('department_id'),
            'position'          =>  $request->input('position')
        ]);

        return $this->show($id);
    }

    public function updateImage(Request $request)
    {
        $request->validate([
            'image' => 'required',
        ]);

        $user = User::find(auth()->user()->id);

        $image = $request->file('image');

        $imageName = uniqid().time().'.'.$image->getClientOriginalExtension();

        $request->image->move(public_path('images'), $imageName);

        $user->update([
            'profile_image' =>  $imageName
        ]);

        return $this->show(auth()->user()->id);
    }

    public function destroy($id)
    {
        $deletedEmployee = Employee::find($id)->delete();

        return new EmployeeResource($deletedEmployee);
    }

    public function registeredEmployees()
    {
        $employees = Employee::all();

        return EmployeeResourceWithCompleteDetails::collection($employees);
    }
}
