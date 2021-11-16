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

        $this->branch = $branchRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branches = Branch::orderBy('created_at', 'desc')
            ->get()
            ->map
            ->format();

        return response()->json($branches);
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

        $branch = Branch::create([
            'name'          =>  $request->input('name'),
            'display_name'  =>  $request->input('display_name')
        ]);

        return response()->json($branch->format());
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
        $branch = Branch::where('id', $id);
        $branch->update([
            'name'          =>  $request->input('name'),
            'display_name'  =>  $request->input('display_name')
        ]);

        return response()->json($branch->first()->format());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Branch::find($id)->delete();

        return response()->json($id);
    }
}
