<?php

namespace App\Http\Controllers\Api\Administrator;

use App\Credit;
use App\Http\Resources\Leave\LeaveResource;
use App\Http\Resources\Leave\LeaveResourceWithEmployeeAndActions;
use App\Http\Resources\Leave\LeaveResourceWithEmployeeDetails;
use App\Http\Resources\Leave\LeaveResourceWithFullEmployeeAndActions;
use App\Leave;
use App\Notifications\Supervisor\LeaveApproveNotification;
use App\Notifications\Supervisor\LeaveDisapprovedNotification;
use App\Repositories\Leave\ILeaveRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class LeaveController extends Controller
{
    public function getEmployeeLeave()
    {
        $leaves = $this->getLeavesByRole('employee');

        return LeaveResourceWithFullEmployeeAndActions::collection($leaves->sortByDesc('created_at'));
    }

    public function getSupervisorLeave()
    {
        $leaves = $this->getLeavesByRole('supervisor');

        return LeaveResourceWithFullEmployeeAndActions::collection($leaves);
    }

    public function approveSupervisorLeave($id)
    {
        $leave = Leave::find($id);
        $credit = Credit::where('user_id', $leave->user_id);

        switch ($leave->type) {
            case 'VL':
                $credit->update([
                    'VL'    =>  $credit->first()->VL - $leave->count
                ]);
                break;
            case 'SL':
                $credit->update([
                    'SL'    =>  $credit->first()->SL - $leave->count
                ]);
                break;
            case 'PTO':
                $credit->update([
                    'PTO'   =>  $credit->first()->PTO - $leave->count
                ]);
                break;
            case 'VL - Half':
                $credit->update([
                    'VL'    =>  $credit->first()->VL - 0.5
                ]);
                break;
            case 'SL - Half':
                $credit->update([
                    'SL'    =>  $credit->first()->SL - 0.5
                ]);
                break;
            case 'PTO - Half':
                $credit->update([
                    'PTO'   =>  $credit->first()->PTO - 0.5
                ]);
                break;
        }

        $leave->update([
            'final_approval' =>  'Approved'
        ]);

        Notification::route('mail', User::find($leave->user_id)->email)->notify(new LeaveApproveNotification());

        return new LeaveResourceWithFullEmployeeAndActions(Leave::where('id', $id)->first());
    }

    public function disapproveSupervisorLeave($id)
    {
        $leave = Leave::find($id);
        $leave->update([
            'final_approval' =>  'Disapproved'
        ]);

        Notification::route('mail', User::find($leave->user_id)->email)->notify(new LeaveDisapprovedNotification());

        return response()->json([
            'message'   =>  'Leave Disapproved By Administrator'
        ]);
    }

    public function filterSupervisorLeave(Request $request)
    {
        $leaves = $this->filterLeaves('supervisor', $request->input('date_from'), $request->input('date_to'), $request->input('status'));
        return LeaveResourceWithFullEmployeeAndActions::collection($leaves);
    }

    public function filterEmployeeLeave(Request $request)
    {
        $leaves = $this->filterLeaves('employee', $request->input('date_from'), $request->input('date_to'), $request->input('status'));
        return LeaveResourceWithFullEmployeeAndActions::collection($leaves);
    }

    private function getLeavesByRole($role)
    {
        $leaves = Leave::join('users', 'leaves.user_id', '=', 'users.id')
                        ->where('users.role', '=', $role)
                        ->select('leaves.*')
                        ->get();

        return $leaves;
    }

    private function filterLeaves($role, $date_from, $date_to, $status)
    {
        $leaves = Leave::join('users', 'leaves.user_id', '=', 'users.id')
                        ->where('users.role', '=', $role)
                        ->where('leaves.final_approval', $status)
                        ->whereBetween('leaves.created_at', [$date_from, $date_to])
                        ->get();

        return $leaves;
    }
}
