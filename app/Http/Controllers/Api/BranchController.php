<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Branch\IBranchRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Branch;
use App\Http\Resources\Branch\BranchResource;

class BranchController extends Controller
{
    private $branch;

    public function __construct(IBranchRepository $branchRepository)
    {
        $this->middleware('auth:api');

        $this->branch = $branchRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branches = $this->branch->allBranches();

        return BranchResource::collection($branches);
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

        $storedBranch = $this->branch->saveBranch($request->all());

        return new BranchResource($storedBranch);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $selectedBranch = $this->branch->getOneBranch(['id' => $id]);

        return new BranchResource($selectedBranch);
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
        $updatedBranch = $this->branch->updateBranch(['id' => $id], [
            'name'  =>  $request->input('name'),
            'display_name'  =>  $request->input('display_name')
        ]);

        return response()->json([
            'message'   =>  'Branch Updated'
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
        $deletedBranch = Branch::find($id)->delete();

        return response()->json([
            'message'   =>  'Branch Deleted'
        ], 200);
    }
}
