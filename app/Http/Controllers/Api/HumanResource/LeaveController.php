<?php

namespace App\Http\Controllers\Api\HumanResource;

use App\Http\Resources\Leave\LeaveResource;
use App\Http\Resources\Leave\LeaveResourceWithActions;
use App\Http\Resources\Leave\LeaveResourceWithEmployeeAndActions;
use App\Leave;
use App\Notifications\HumanResource\LeaveApproveNotification;
use App\Notifications\HumanResource\LeaveDisapproveNotification;
use App\Repositories\Credit\ICreditRepository;
use App\Repositories\Leave\ILeaveRepository;
use App\Repositories\User\IUserRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;

class LeaveController extends Controller
{
    private $leave;
    private $credit;
    private $user;

    public function __construct(IUserRepository $userRepository, ILeaveRepository $leaveRepository, ICreditRepository $creditRepository)
    {
        $this->middleware('auth:api');
        $this->leave = $leaveRepository;
        $this->credit = $creditRepository;
        $this->user = $userRepository;
    }

    public function index()
    {
        $leaves = $this->leave->getLeaveBy(['recommending_approval' => 'Approved']);

        return LeaveResourceWithEmployeeAndActions::collection($leaves);
    }

    public function store(Request $request)
    {
        $storedLeave = $this->leave->saveLeave($request->all());

        return new LeaveResource($storedLeave);
    }

    public function show($id)
    {

    }

    public function update(Request $request, $id)
    {
        $updateLeave = $this->leave->updateLeave(['id' => $id], $request->all());

        return response()->json([
            'message'   =>  'Leave Updated!'
        ], 200);
    }

    public function destroy($id)
    {
        //
    }

    public function approve($id)
    {
        $leave = $this->leave->getLeaveById($id);
        $this->leave->approveFinalApproval($id);

        switch ($leave->type) {
            case 'VL':
                $this->credit->updateVacationLeave($leave->user_id, $leave->count);
            break;

            case 'SL':
                $this->credit->updateSickLeave($leave->user_id, $leave->count);
            break;

            case 'PTO':
                $this->credit->updatePersonalTimeOff($leave->user_id, $leave->count);
            break;

            case 'VL-Half':
                $this->credit->updateVacationLeave($leave->user_id, 0.5);
            break;

            case 'SL-Half':
                $this->credit->updateSickLeave($leave->user_id, 0.5);
            break;

            case 'PTO-Half':
                $this->credit->updatePersonalTimeOff($leave->user_id, 0.5);
            break;
        }

        Notification::route('mail', User::find($leave->user_id)->email)->notify(new LeaveApproveNotification());

        return response()->json([
            'message'   =>  'VL Approved!'
        ], 200);
    }

    public function disapprove($id)
    {
        $leave = $this->leave->getLeaveById($id);
        $this->leave->disapproveFinalApproval($id);

        Notification::route('mail', User::find($leave->user_id)->email)->notify(new LeaveDisapproveNotification());

        return response()->json([
            'message'   =>  'VL Disapproved!'
        ], 200);
    }

    public function filterLeave(Request $request)
    {
        $leaves = $this->leave->filterLeaves($request->input('date_from'), $request->input('date_to'), $request->input('status'));
        return LeaveResourceWithEmployeeAndActions::collection($leaves);
    }
}
