<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Department\IDepartmentRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department;
use App\Http\Resources\Department\DepartmentCollection;
use App\Http\Resources\Department\DepartmentResource;

class DepartmentController extends Controller
{
    private $department;

    public function __construct(IDepartmentRepository $departmentRepository)
    {
        $this->department = $departmentRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = $this->department->allDepartments();

        return DepartmentResource::collection($departments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          =>  'required',
            'display_name'  =>  'required'
        ]);

        $storedDepartment = $this->department->saveDepartment($request->all());

        return new DepartmentResource($storedDepartment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $selectedDepartment = $this->department->getOneDepartment(['id' => $id]);

        return new DepartmentResource($selectedDepartment);
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
        $updatedDepartment = $this->department->updateDepartment(['id' => $id], [
            'id'            =>  $request->input('id'),
            'name'          =>  $request->input('name'),
            'display_name'  =>  $request->input('display_name'),
            'supervisor_id' =>  $request->input('supervisor_id')
        ]);

        return response()->json([
            'message'   =>  'Department Update'
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
        $deletedDepartment = Department::find($id)->delete();

        return response()->json([
            'message'   =>  'Department Deleted'
        ], 200);
    }
}
