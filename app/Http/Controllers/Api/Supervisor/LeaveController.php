<?php

namespace App\Http\Controllers\Api\Supervisor;

use App\Credit;
use App\Http\Resources\Leave\LeaveResource;
use App\Http\Resources\Leave\LeaveResourceWithEmployeeAndActions;
use App\Http\Resources\Leave\LeaveResourceWithEmployeeAndActionsForSup;
use App\Http\Resources\Leave\LeaveResourceWithEmployeeDetails;
use App\Leave;
use App\Notifications\Employee\LeaveEmToSupNotification;
use App\Notifications\Supervisor\LeaveApproveNotification;
use App\Notifications\Supervisor\LeaveDisapprovedNotification;
use App\Repositories\Leave\ILeaveRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $leaves = Leave::join('users', 'users.id', '=', 'leaves.user_id')
                        ->join('employees', 'leaves.user_id', '=', 'employees.user_id')
                        ->join('departments', 'employees.department_id', '=', 'departments.id')
                        ->select('leaves.*')
                        ->where('departments.supervisor_id', '=', auth()->user()->id)
                        ->where('users.role', '=', 'employee')
                        ->get();

        return LeaveResourceWithEmployeeAndActionsForSup::collection($leaves->sortByDesc('created_at'));
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
            'count'     =>  $diff->d == 0 ? 1 : $diff->d
        ];

        $credit = Credit::where('user_id', auth()->user()->id)->first();

        if ($request->input('pay_type') == 'With Pay') {
            switch ($request->input('type')) {
                case 'VL':
                    if ($credit->VL > 0) {
                        $this->leave->saveLeave($data);
                        
                        if ($diff->d > 1) {
                            return response()->json([
                                'message'   =>  'Your request has been sent and is waiting to approval. Make sure to inform the Management of you leave request.'
                            ], 200);
                        } else {
                            return response()->json([
                                'message'   =>  'Leave Submitted!'
                            ], 200);
                        }
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Vacation Leave(s)'
                        ], 401);
                    }
                    break;

                case 'SL':
                    if ($credit->SL > 0) {
                        $this->leave->saveLeave($data);
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Sick Leave(s)'
                        ], 401);
                    }
                    break;

                case 'PTO':
                    if ($credit->PTO > 0) {
                        $this->leave->saveLeave($data);

                        if ($diff->d > 1) {
                            return response()->json([
                                'message'   =>  'Your request has been sent and is waiting to approval. Make sure to inform the Management of you leave request.'
                            ], 200);
                        } else {
                            return response()->json([
                                'message'   =>  'Leave Submitted!'
                            ], 200);
                        }
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Personal Time Off(s)'
                        ], 401);
                    }
                    break;
                case 'Special':
                    if ($credit->special_leave > 0) {
                        $this->leave->saveLeave($data);

                        if ($diff->d > 1) {
                            return response()->json([
                                'message'   =>  'Your request has been sent and is waiting to approval. Make sure to inform the Management of you leave request.'
                            ], 200);
                        } else {
                            return response()->json([
                                'message'   =>  'You don\'t have Special Leave(s)'
                            ], 401);
                        }
                    }
                    break;
                case 'VL - Half':
                    if ($credit->VL > 0) {
                        $this->leave->saveLeave($data);
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Vacation Leave(s)'
                        ], 401);
                    }
                    break;

                case 'SL - Half':
                    if ($credit->SL > 0) {
                        $this->leave->saveLeave($data);
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Sick Leave(s)'
                        ], 401);
                    }
                    break;

                case 'PTO - Half':
                    if ($credit->PTO > 0) {
                        $this->leave->saveLeave($data);
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Personal Time Off(s)'
                        ], 401);
                    }
                    break;
                
                case 'Special - Half':
                    if ($credit->special_leave > 0) {
                        $this->leave->saveLeave($data);
                    } else {
                        return response()->json([
                            'message'   =>  'You don\'t have Special Leave(s)'
                        ], 401);
                    }
                    break;
            }
        } else {
            $this->leave->saveLeave($data);

            return response()->json([
                'message'   =>  'Leave Submitted!'
            ], 200);
        }

        if ($diff->d > 3) {
            return response()->json([
                'message'   =>  'You can\'t file more than 3 leaves.'
            ], 401);
        } else {
            $storedLeave = Leave::create([
                'user_id'               =>  auth()->user()->id,
                'type'                  =>  $request->input('type'),
                'pay_type'              =>  $request->input('pay_type'),
                'from'                  =>  $request->input('from'),
                'to'                    =>  $request->input('to'),
                'time_from'             =>  $request->input('time_from'),
                'time_to'               =>  $request->input('time_to'),
                'reason'                =>  $request->input('reason'),
                'count'                 =>  ($request->input('from') == $request->input('to')) ? 1 : $diff->d,
                'recommending_approval' =>  'Approved',
                'final_approval'        =>  'Pending'
            ]);

            Notification::route('mail', env('ADMIN_EMAIL'))->notify(new LeaveEmToSupNotification($storedLeave));

            return response()->json([
                'message'   =>  'Your request has been sent and is waiting to approval. Make sure to inform the Management of you leave request.'
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
        //
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
            'count'     =>  $diff->d == 0 ? 1 : $diff->d
        ];

        if ($diff->d > 3) {
            return response()->json([
                'message'   =>  'You can\'t file more than 3 leaves.'
            ], 401);
        } else {
            Leave::find($id)
                ->update($data);

            return response()->json([
                'message'   =>  'Updated Leave'
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Leave::find($id)->delete();

        return response()->json([
            'message'   =>  'Leave Deleted'
        ], 200);
    }

    public function approve($id)
    {
        $this->leave->approveRecommendingApproval($id);
        $leave = $this->leave->getOneLeave(['id' => $id]);

        Notification::route('mail', User::find($leave->user_id)->email)->notify(new LeaveApproveNotification());
        return response()->json([
            'message'   =>  'Leave Approved!'
        ], 200);
    }

    public function disapprove($id)
    {
        $this->leave->disapproveRecommendingApproval($id);
        $leave = $this->leave->getOneLeave(['id' => $id]);

        Notification::route('mail', User::find($leave->user_id)->email)->notify(new LeaveDisapprovedNotification());
        return response()->json([
            'message'   =>  'Leave Disapproved!'
        ], 200);
    }
}
