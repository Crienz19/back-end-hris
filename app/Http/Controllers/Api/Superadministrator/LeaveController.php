<?php

namespace App\Http\Controllers\Api\Superadministrator;

use App\Repositories\Leave\ILeaveRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Leave;
use App\Http\Resources\Leave\LeaveResourceWithEmployeeDetails;
use App\Http\Resources\Leave\LeaveResource;

class LeaveController extends Controller
{
    private $leave;
    public function __construct(ILeaveRepository $leaveRepository)
    {
        $this->middleware('auth:api');
        $this->leave = $leaveRepository;
    }
    
    public function index()
    {
        $leaves = $this->leave->allLeaves();

        return LeaveResourceWithEmployeeDetails::collection($leaves);
    }

    public function store(Request $request)
    {
        $storedLeave = $this->leave->saveLeave($request->all());

        return new LeaveResource($storedLeave);
    }

    public function show($id)
    {
        $selectedLeave = $this->leave->getOneLeave(['id' => $id]);

        return new LeaveResourceWithEmployeeDetails($selectedLeave);
    }

    public function update(Request $request, $id)
    {
        $updatedLeave = $this->leave->updateLeave(['id' => $id], $request->all());

        return response()->json([
            'message'   =>  'Leave Updated!'
        ], 200);
    }

    public function destroy($id)
    {
        $deletedLeave = $this->leave->deleteLeave($id);

        return response()->json([
            'message'   =>  'Leave Deleted'
        ], 200);
    }
}
