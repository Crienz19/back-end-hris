<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Role\RoleResource;
use App\Repositories\Role\IRoleRepository;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    private $role;

    public function __construct(IRoleRepository $roleRepository)
    {
        $this->role = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = Role::orderBy('created_at', 'desc')
            ->get()
            ->map
            ->format();

        return response()->json($role);
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

        $role = Role::create([
            'name'          =>  $request->input('name'),
            'display_name'  =>  $request->input('display_name')
        ]);

        return response()->json($role->format());
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
        $request->validate([
            'name'          =>  'required',
            'display_name'  =>  'required'
        ]);

        $role = Role::where('id', $id);
        $role->update([
            'name'          =>  $request->has('name') ? $request->input('name') : $role->first()->name,
            'display_name'  =>  $request->has('display_name') ? $request->input('display_name') : $role->first()->display_name
        ]);

        return response()->json($role->first()->format());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Role::where('id', $id)->delete();

        return response()->json($id);
    }
}
