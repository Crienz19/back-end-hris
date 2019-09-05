<?php

namespace App\Http\Controllers\Api\Supervisor;

use App\Department;
use App\Http\Resources\Overtime\OvertimeResource;
use App\Http\Resources\Overtime\OvertimeResourceWithEmployeeAndActionsForSup;
use App\Http\Resources\Overtime\OvertimeResourceWithEmployeeDetails;
use App\Notifications\Employee\OvertimeEmToSupNotification;
use App\Notifications\Supervisor\OvertimeApproveNotification;
use App\Notifications\Supervisor\OvertimeDisapproveNotification;
use App\Overtime;
use App\Repositories\Overtime\IOvertimeRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class OvertimeController extends Controller
{
    private $overtime;
    public function __construct(IOvertimeRepository $overtimeRepository)
    {
        $this->middleware('auth:api');
        $this->overtime = $overtimeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $overtimes = Overtime::join('users', 'users.id', '=', 'overtimes.user_id')
                            ->join('employees', 'overtimes.user_id', '=', 'employees.user_id')
                            ->join('departments', 'employees.department_id', '=', 'departments.id')
                            ->select('overtimes.*')
                            ->where('departments.supervisor_id', '=', auth()->user()->id)
                            ->where('users.role', '=','employee')
                            ->get();

        return OvertimeResourceWithEmployeeAndActionsForSup::collection($overtimes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $overtime = Overtime::create([
            'user_id'   =>  auth()->user()->id,
            'date'      =>  $request->input('date'),
            'from'      =>  $request->input('from'),
            'to'        =>  $request->input('to'),
            'reason'    =>  $request->input('reason')
        ]);

        Notification::route('mail', 'rmergenio@ziptravel.com.ph')->notify(new OvertimeEmToSupNotification($overtime));

        return response()->json([
            'message'   =>  'Overtime Stored!'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $overtime = Overtime::find($id);

        return new OvertimeResourceWithEmployeeDetails($overtime);
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
        Overtime::find($id)->update($request->all());

        return response()->json([
            'message'   =>  'Overtime Updated!'
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
        Overtime::find($id)->delete();

        return response()->json([
            'message'   =>  'Overtime Deleted'
        ], 200);
    }

    public function approve($id)
    {
        $this->overtime->approveOvertime($id);
        $overtime = $this->overtime->getOneOvertime(['id' => $id]);

        Notification::route('mail', User::find($overtime->user_id)->email)->notify(new OvertimeApproveNotification());

        return response()->json([
            'message'   =>  'Overtime Approved'
        ], 200);
    }

    public function disapprove($id)
    {
        $this->overtime->disapproveOvertime($id);

        $overtime = $this->overtime->getOneOvertime(['id' => $id]);

        Notification::route('mail', User::find($overtime->user_id)->email)->notify(new OvertimeDisapproveNotification());

        return response()->json([
            'message'   =>  'Overtime Disapproved'
        ], 200);
    }
}
