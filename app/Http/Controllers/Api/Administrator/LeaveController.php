<?php

namespace App\Http\Controllers\Api\Administrator;

use App\Http\Resources\Leave\LeaveResource;
use App\Http\Resources\Leave\LeaveResourceWithEmployeeAndActions;
use App\Http\Resources\Leave\LeaveResourceWithEmployeeDetails;
use App\Http\Resources\Leave\LeaveResourceWithFullEmployeeAndActions;
use App\Leave;
use App\Repositories\Leave\ILeaveRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{

    public function getEmployeeLeave()
    {
        $leaves = $this->getLeavesByRole('employee');

        return LeaveResourceWithFullEmployeeAndActions::collection($leaves);
    }

    public function getSupervisorLeave()
    {
        $leaves = $this->getLeavesByRole('supervisor');

        return LeaveResourceWithFullEmployeeAndActions::collection($leaves);
    }

    public function approveSupervisorLeave($id)
    {
        Leave::find($id)->update([
            'final_approval' =>  'Approved'
        ]);

        return response()->json([
            'message'   =>  'Leave Approved By Administrator'
        ]);
    }

    public function disapproveSupervisorLeave($id)
    {
        Leave::find($id)->update([
            'final_approval' =>  'Disapproved'
        ]);

        return response()->json([
            'message'   =>  'Leave Disapproved By Administrator'
        ]);
    }

    public function filterSupervisorLeave(Request $request)
    {
        $leaves = $this->filterLeaves('supervisor', $request->input('date_from'), $request->input('date_to'), $request->input('status'));
        return LeaveResourceWithEmployeeAndActions::collection($leaves);
    }

    public function filterEmployeeLeave(Request $request)
    {
        $leaves = $this->filterLeaves('employee', $request->input('date_from'), $request->input('date_to'), $request->input('status'));
        return LeaveResourceWithEmployeeAndActions::collection($leaves);
    }

    private function getLeavesByRole($role)
    {
        $leaves = Leave::join('users', 'leaves.id', '=', 'users.id')
                        ->where('users.role', '=', $role)
                        ->select('leaves.*')
                        ->get();

        return $leaves;
    }

    private function filterLeaves($role, $date_from, $date_to, $status)
    {
        $leaves = Leave::join('users', 'leaves.id', '=', 'users.id')
                        ->where('users.role', '=', $role)
                        ->where('leaves.final_approval', $status)
                        ->whereBetween('leaves.created_at', [$date_from, $date_to])
                        ->get();

        return $leaves;
    }
}
