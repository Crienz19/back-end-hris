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
        $employees = Employee::orderBy('created_at', 'desc')
            ->get()
            ->map
            ->format();

        return response()->json($employees);
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

        return response()->json($storedEmployee->format());
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
        $employee = Employee::where('user_id', $id);
        $employee->update($request->all());

        return response()->json($employee->first()->format());
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
