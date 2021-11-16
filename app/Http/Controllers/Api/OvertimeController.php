<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Leave;
use App\Notifications\Employee\OvertimeEmToSupNotification;
use App\Notifications\Supervisor\OvertimeApproveNotification;
use App\Notifications\Supervisor\OvertimeDisapproveNotification;
use App\Overtime;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class OvertimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $overtimes = Overtime::orderBy('created_at', 'desc')
            ->get()
            ->map
            ->format();

        return response()->json($overtimes);
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
            'date'      =>  ['required'],
            'from'      =>  ['required'],
            'to'        =>  ['required'],
            'reason'    =>  ['required']
        ]);

        $supervisorEmail = \App\Employee::join('departments', 'employees.department_id', '=', 'departments.id')
            ->join('users', 'users.id', '=', 'departments.supervisor_id')
            ->where('employees.user_id', auth()->user()->id)
            ->select('users.email')
            ->first()
            ->email;

        $leave = $this->saveOvertimeRequest([
            'date'      =>  $request->input('date'),
            'from'      =>  $request->input('from'),
            'to'        =>  $request->input('to'),
            'reason'    =>  $request->input('reason'),
            'status'    =>  'Pending'
        ]);

        Notification::route('mail', $supervisorEmail)->notify(new OvertimeEmToSupNotification($leave));

        return response()->json($leave->format());
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
        return response()->json($this->updateOvertimeRequest([
            'date'      =>  $request->input('date'),
            'from'      =>  $request->input('from'),
            'to'        =>  $request->input('to'),
            'reason'    =>  $request->input('reason'),
            'status'    =>  'Pending'
        ], $id)->format());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Overtime::where('id', $id)->delete();

        return response()->json($id);
    }

    private function saveOvertimeRequest($data)
    {

        $leave = auth()->user()->overtime()->create($data);

        return $leave;
    }

    public function approveStatus($id)
    {
        $overtime = Overtime::where('id', $id);
        $overtime->update([
            'status'    =>  'Approved'
        ]);

        Notification::route('mail', User::find($overtime->first()->user_id)->email)->notify(new OvertimeApproveNotification());

        return response()->json($overtime->first()->adminFormat());
    }

    public function disapproveStatus($id)
    {
        $overtime = Overtime::where('id', $id);
        $overtime->update([
            'status'    =>  'Disapproved'
        ]);

        Notification::route('mail', User::find($overtime->first()->user_id)->email)->notify(new OvertimeDisapproveNotification());

        return response()->json($overtime->first()->adminFormat());
    }

    public function filterOvertime(Request $request)
    {
        $overtime = Overtime::where('status', $request->input('status'))
            ->whereBetween('date', [$request->input('date_from'), $request->input('date_to')])
            ->get()
            ->map
            ->format();

        return response()->json($overtime);
    }

    private function updateOvertimeRequest($data, $id)
    {
        $overtime = Overtime::where('id', $id);
        $overtime->update($data);

        return $overtime->first();
    }
}
