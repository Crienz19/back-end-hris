<?php

namespace App\Http\Controllers\Api\Employee;

use App\Credit;
use App\Employee;
use App\Http\Resources\Leave\LeaveResourceWithUpdateDelete;
use App\Notifications\Employee\LeaveEmToSupNotification;
use App\Repositories\Leave\ILeaveRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Leave;
use App\Http\Resources\Leave\LeaveResource;
use Illuminate\Support\Facades\Notification;

class LeaveController extends Controller
{
    private $leave;
    public function __construct(ILeaveRepository $leaveRepository)
    {
        $this->middleware('auth:api');
        $this->leave = $leaveRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leaves = $this->leave->getLeaveBy(['user_id' => auth()->user()->id]);

        return LeaveResourceWithUpdateDelete::collection($leaves->sortByDesc('created_at'));
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
            'count'     =>  ($request->input('from') == $request->input('to')) ? 1 : $diff->d
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
                        $this->leave->saveLeave($data);
                        Notification::route('mail', $supervisorEmail)->notify(new LeaveEmToSupNotification($data));
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Vacation Leave(s)'
                        ], 401);
                    }
                    break;

                case 'SL':
                    if ($credit->SL > 0) {
                        $this->leave->saveLeave($data);
                        Notification::route('mail', $supervisorEmail)->notify(new LeaveEmToSupNotification($data));
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Sick Leave(s)'
                        ], 401);
                    }
                    break;

                case 'PTO':
                    if ($credit->PTO > 0) {
                        $this->leave->saveLeave($data);
                        Notification::route('mail', $supervisorEmail)->notify(new LeaveEmToSupNotification($data));
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Personal Time Off(s)'
                        ], 401);
                    }
                    break;
                case 'VL-Half':
                    if ($credit->VL > 0) {
                        $this->leave->saveLeave($data);
                        Notification::route('mail', $supervisorEmail)->notify(new LeaveEmToSupNotification($data));
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Vacation Leave(s)'
                        ], 401);
                    }
                    break;

                case 'SL-Half':
                    if ($credit->SL > 0) {
                        $this->leave->saveLeave($data);
                        Notification::route('mail', $supervisorEmail)->notify(new LeaveEmToSupNotification($data));
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Sick Leave(s)'
                        ], 401);
                    }
                    break;

                case 'PTO-Half':
                    if ($credit->PTO > 0) {
                        $this->leave->saveLeave($data);
                        Notification::route('mail', $supervisorEmail)->notify(new LeaveEmToSupNotification($data));
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Personal Time Off(s)'
                        ], 401);
                    }
                    break;

            }
        } else {
            $this->leave->saveLeave($data);
            Notification::route('mail', $supervisorEmail)->notify(new LeaveEmToSupNotification($data));

            return response()->json([
                'message'   =>  'Leave Submitted!'
            ], 200);
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
        $selectedLeave = $this->leave->getOneLeave(['id' => $id]);

        return new LeaveResource($selectedLeave);
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
            'user_id'   =>  auth()->user()->id,
            'type'      =>  $request->input('type'),
            'pay_type'  =>  $request->input('pay_type'),
            'from'      =>  $request->input('from'),
            'to'        =>  $request->input('to'),
            'time_from' =>  $request->input('time_from'),
            'time_to'   =>  $request->input('time_to'),
            'reason'    =>  $request->input('reason'),
            'count'     =>  ($request->input('from') == $request->input('to')) ? 1 : $diff->d
        ];

        $updatedLeave = $this->leave->updateLeave(['id' => $id], $data);

        return response()->json([
            'message'   =>  'Leave Updated'
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
        $deletedLeave = $this->leave->deleteLeave($id);

        return response()->json([
            'message'   =>  'Leave Deleted'
        ], 200);
    }
}
