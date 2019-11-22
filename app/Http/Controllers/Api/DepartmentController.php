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
        $this->middleware('auth:api');
        $this->department = $departmentRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::orderBy('created_at', 'desc')
            ->get()
            ->map
            ->format();

        return response()->json($departments);
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

        $department = Department::create([
            'name'          =>  $request->input('name'),
            'display_name'  =>  $request->input('display_name')
        ]);

        return response()->json($department->format());
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
        $request->validate([
            'name'          =>  ['required'],
            'display_name'  =>  ['required']
        ]);

        $department = Department::where('id', $id);
        $department->update([
            'name'      =>  $request->has('name') ? $request->input('name') : $department->first()->name,
            'display_name'  =>  $request->has('display_name') ? $request->input('display_name') : $department->first()->display_name
        ]);

        return response()->json($department->first()->format());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Department::where('id', $id)->delete();

        return response()->json($id);
    }
}
