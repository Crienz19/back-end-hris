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
        $this->leave = $leaveRepository;
    }
    
    public function index()
    {
        $leaves = $this->leave->allLeaves();

        return LeaveResourceWithEmployeeDetails::collection($leaves->sortByDesc('created_at'));
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
        $updatedLeave = $this->leave->updateLeave(['id' => $id], [
            'type'                  =>  $request->input('type'),
            'pay_type'              =>  $request->input('pay_type'),
            'from'                  =>  $request->input('from'),
            'to'                    =>  $request->input('to'),
            'time_from'             =>  $request->input('time_from'),
            'time_to'               =>  $request->input('time_to'),
            'reason'                =>  $request->input('reason'),
            'count'                 =>  $request->input('count'),
            'recommending_approval' =>  $request->input('recommending_approval'),
            'final_approval'        =>  $request->input('final_approval')
        ]);

        return new LeaveResourceWithEmployeeDetails($this->leave->getOneLeave(['id' => $id]));
    }

    public function destroy($id)
    {
        $deletedLeave = $this->leave->deleteLeave($id);

        return response()->json([
            'message'   =>  'Leave Deleted'
        ], 200);
    }
}
