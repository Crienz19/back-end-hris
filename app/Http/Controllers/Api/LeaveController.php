<?php

namespace App\Http\Controllers\Api;

use App\Credit;
use App\Http\Resources\Leave\LeaveResourceWithEmployeeAndActions;
use App\Http\Resources\Leave\LeaveResourceWithEmployeeDetails;
use App\Leave;
use App\Notifications\Employee\LeaveEmToSupNotification;
use App\Notifications\HumanResource\LeaveDisapproveNotification;
use App\Notifications\Supervisor\LeaveApproveNotification;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leaves = Leave::orderBy('created_at', 'desc')
            ->get()
            ->map
            ->format();

        return response()->json($leaves);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dt1 = new \DateTime($request->input('from'));
        $dt2 = new \DateTime($request->input('to'));
        $diff = $dt1->diff($dt2);

        $data = [
            'user_id'   =>  auth()->user()->id,
            'type'      =>  $request->input('type'),
            'pay_type'  =>  $request->input('pay_type'),
            'from'      =>  $request->input('from'),
            'to'        =>  $request->input('to'),
            'time_from' =>  $request->input('time_from'),
            'time_to'   =>  $request->input('time_to'),
            'reason'    =>  $request->input('reason'),
            'count'     =>  $diff->d == 0 ? 1 : $diff->d,
            'recommending_approval'     =>  (auth()->user()->role == 'supervisor') ? 'Approved' : 'Pending',
            'final_approval'            =>  'Pending'
        ];

        $credit = Credit::where('user_id', auth()->user()->id)->first();
        $supervisorEmail = \App\Employee::join('departments', 'employees.department_id', '=', 'departments.id')
            ->join('users', 'users.id', '=', 'departments.supervisor_id')
            ->where('employees.user_id', auth()->user()->id)
            ->select('users.email')
            ->first()
            ->email;

        if ($request->input('pay_type') == 'With Pay') {
            switch ($request->input('type')) {
                case 'VL':
                    if ($credit->VL > 0) {
                        $leave = $this->saveUserLeaveRequest($data);
                        Notification::route('mail', $supervisorEmail)->notify(new LeaveEmToSupNotification($data));

                        return response()->json($leave->format());
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Vacation Leave(s)'
                        ], 401);
                    }
                    break;

                case 'SL':
                    if ($credit->SL > 0) {
                        $leave = $this->saveUserLeaveRequest($data);
                        Notification::route('mail', $supervisorEmail)->notify(new LeaveEmToSupNotification($data));

                        return response()->json($leave->format());
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Sick Leave(s)'
                        ], 401);
                    }
                    break;

                case 'PTO':
                    if ($credit->PTO > 0) {
                        $leave = $this->saveUserLeaveRequest($data);
                        Notification::route('mail', $supervisorEmail)->notify(new LeaveEmToSupNotification($data));

                        return response()->json($leave);
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Personal Time Off(s)'
                        ], 401);
                    }
                    break;
                case 'VL - Half':
                    if ($credit->VL > 0) {
                        $leave = $this->saveUserLeaveRequest($data);
                        Notification::route('mail', $supervisorEmail)->notify(new LeaveEmToSupNotification($data));

                        return response()->json($leave);
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Vacation Leave(s)'
                        ], 401);
                    }
                    break;

                case 'SL - Half':
                    if ($credit->SL > 0) {
                        $leave = $this->saveUserLeaveRequest($data);
                        Notification::route('mail', $supervisorEmail)->notify(new LeaveEmToSupNotification($data));

                        return response()->json($leave);
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Sick Leave(s)'
                        ], 401);
                    }
                    break;

                case 'PTO - Half':
                    if ($credit->PTO > 0) {
                        $leave = $this->saveUserLeaveRequest($data);
                        Notification::route('mail', $supervisorEmail)->notify(new LeaveEmToSupNotification($data));

                        return response()->json($leave);
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Personal Time Off(s)'
                        ], 401);
                    }
                    break;

                case 'Special':
                    if ($credit->special_leave > 0) {
                        $this->leave->saveLeave($data);
                        Notification::route('mail', $supervisorEmail)->notify(new LeaveEmToSupNotification($data));
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Special Leave(s)'
                        ], 401);
                    }
                    break;

                case 'Special - Half':
                    if ($credit->special_leave > 0) {
                        $this->leave->saveLeave($data);
                        Notification::route('mail', $supervisorEmail)->notify(new LeaveEmToSupNotification($data));
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Special Leave(s)'
                        ], 401);
                    }
                    break;

            }
        } else {
            $leave = $this->saveUserLeaveRequest($data);
            Notification::route('mail', $supervisorEmail)->notify(new LeaveEmToSupNotification($data));

            return response()->json($leave);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
        $dt1 = new \DateTime($request->input('from'));
        $dt2 = new \DateTime($request->input('to'));
        $diff = $dt1->diff($dt2);

        $data = [
            'type'      =>  $request->input('type'),
            'pay_type'  =>  $request->input('pay_type'),
            'from'      =>  $request->input('from'),
            'to'        =>  $request->input('to'),
            'time_from' =>  $request->input('time_from'),
            'time_to'   =>  $request->input('time_to'),
            'reason'    =>  $request->input('reason'),
            'count'     =>  $diff->d == 0 ? 1 : $diff->d
        ];

        return response()->json($this->updateUserLeaveRequest($data, $id)->format());

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json($this->deleteLeaveRequest($id));
    }

    public function approveRecommendingApproval($id)
    {
        $leave = Leave::where('id', $id);

        $leave->update([
            'recommending_approval'     =>  'Approved'
        ]);

        Notification::route('mail', User::find($leave->first()->user_id)->email)->notify(new LeaveApproveNotification());

        return response()->json($leave->first()->format());
    }

    public function disapproveRecommendingApproval($id)
    {
        $leave = Leave::where('id', $id);

        $leave->update([
            'recommending_approval'     =>  'Disapproved'
        ]);

        Notification::route('mail', User::find($leave->first()->user_id)->email)->notify(new LeaveDisapproveNotification());

        return response()->json($leave->first()->format());
    }

    public function approveFinalApproval($id)
    {
        $leave = Leave::where('id', $id);
        $credit = Credit::where('user_id', $leave->first()->user_id);
        if ($leave->first()->pay_type == 'With Pay') {
            switch ($leave->first()->type) {
                case 'VL':
                    $credit->update([
                        'VL'    =>  $credit->first()->VL - $leave->first()->count
                    ]);
                    break;

                case 'VL-Half':
                    $credit->update([
                        'VL'    =>  $credit->first()->VL - 0.5
                    ]);
                    break;

                case 'SL':
                    $credit->update([
                        'SL'    =>  $credit->first()->SL - $leave->first()->count
                    ]);
                    break;

                case 'SL-Half':
                    $credit->update([
                        'SL'    =>  $credit->first()->SL - 0.5
                    ]);
                    break;

                case 'PTO':
                    $credit->update([
                        'PTO'   =>  $credit->first()->PTO - $leave->first()->count
                    ]);
                    break;

                case 'PTO-Half':
                    $credit->update([
                        'PTO'   =>  $credit->first()->PTO - 0.5
                    ]);
                    break;

                case 'Special':
                    $credit->update([
                        'special_leave' =>  $credit->first()->special_leave - $leave->first()->count
                    ]);
                    break;

                case 'Special - Half':
                    $credit->update([
                        'special_leave' =>  $credit->first()->special_leave - 0.5
                    ]);
                    break;
            }

            $leave->update([
                'final_approval'     =>  'Approved'
            ]);

            Notification::route('mail', User::find($leave->first()->user_id)->email)->notify(new LeaveApproveNotification());

            return response()->json($leave->first()->format());
        } else {
            $leave->update([
                'final_approval'     =>  'Approved'
            ]);

            Notification::route('mail', User::find($leave->first()->user_id)->email)->notify(new LeaveApproveNotification());

            return response()->json($leave->first()->format());
        }
    }

    public function disapproveFinalApproval($id)
    {
        $leave = Leave::where('id', $id);

        $leave->update([
            'final_approval'     =>  'Disapproved'
        ]);

        Notification::route('mail', User::find($leave->first()->user_id)->email)->notify(new LeaveDisapproveNotification());

        return response()->json($leave->first()->format());
    }

    public function filterLeave(Request $request)
    {
        $leave = Leave::where('final_approval', $request->input('status'))
            ->whereBetween('created_at', [$request->input('date_from'), $request->input('date_to')])
            ->get()
            ->map
            ->format();

        return response()->json($leave);
    }

    private function saveUserLeaveRequest($data)
    {
        $leave = auth()->user()->leave()->create($data);

        return $leave;
    }

    private function updateUserLeaveRequest($data, $id)
    {
        $leave = Leave::where('id', $id);
        $leave->update($data);

        return $leave->first();
    }

    private function deleteLeaveRequest($id)
    {
        $leave = Leave::where('id', $id);
        $leave->delete();

        return $id;
    }
}
