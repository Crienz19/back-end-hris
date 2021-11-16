<?php

namespace App\Http\Controllers\Api\Employee;

use App\Employee;
use App\Http\Resources\Employee\EmployeeResource;
use App\Repositories\Employee\IEmployeeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    private $employee;

    public function __construct(IEmployeeRepository $employeeRepository)
    {
        $this->employee = $employeeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employee = $this->employee->getOneEmployee(['user_id' => auth()->user()->id]);

        return new EmployeeResource($employee);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $storedEmployees = $this->employee->saveEmployee($request->all());

        return new EmployeeResource($storedEmployees);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $selectedEmployee = $this->employee->getOneEmployee(['id' => $id]);

        return new EmployeeResource($selectedEmployee);
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
        $updateEmployee = $this->employee->updateEmployee(['id' => $id], $request->all());

        return response()->json([
            'message'   =>  'Employee Updated'
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
        $deletedEmployee = $this->employee->deleteEmployee($id);

        return response()->json([
            'message'   =>  'Employee Deleted'
        ], 200);
    }
}
