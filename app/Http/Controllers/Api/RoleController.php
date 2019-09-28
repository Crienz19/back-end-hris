<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Role\RoleResource;
use App\Repositories\Role\IRoleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    private $role;

    public function __construct(IRoleRepository $roleRepository)
    {
        $this->middleware('auth:api');
        $this->role = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->role->allRoles();

        return RoleResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $storedRole = $this->role->saveRole($request->all());

        return new RoleResource($storedRole);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $selectedRole = $this->role->getOneRole(['id' => $id]);

        return new RoleResource($selectedRole);
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
        $updatedRole = $this->role->updateRole(['id' => $id], [
            'name'  =>  $request->input('name'),
            'display_name'  =>  $request->input('display_name')
        ]);

        return response()->json([
            'message'   =>  'Role Updated!'
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
        $deletedRole = $this->role->deleteRole($id);

        return response()->json([
            'message'   =>  'Role Deleted!'
        ], 200);
    }
}
