<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Resources\Overtime\OvertimeResource;
use App\Http\Resources\Overtime\OvertimeResourceWithUpdateDelete;
use App\Notifications\Employee\OvertimeEmToSupNotification;
use App\Repositories\Overtime\IOvertimeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $overtimes = $this->overtime->getOvertimeBy(['user_id' => auth()->user()->id]);

        return OvertimeResourceWithUpdateDelete::collection($overtimes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
            'user_id'   =>  auth()->user()->id,
            'date'      =>  $request->input('date'),
            'from'      =>  $request->input('from'),
            'to'        =>  $request->input('to'),
            'reason'    =>  $request->input('reason')
        ];

        $storedOvertime = $this->overtime->saveOvertime($data);

        Notification::route('mail', 'rmergenio@ziptravel.com.ph')->notify(new OvertimeEmToSupNotification($data));

        return new OvertimeResource($storedOvertime);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $selectedOvertime = $this->overtime->getOneOvertime(['id' => $id]);

        return new OvertimeResource($selectedOvertime);
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
        $data = [
            'date'      =>  $request->input('date'),
            'from'      =>  $request->input('from'),
            'to'        =>  $request->input('to'),
            'reason'    =>  $request->input('reason')
        ];

        $updateOvertime = $this->overtime->updateOvertime(['id' => $id], $data);

        return response()->json([
            'message'   =>  'Overtime Updated'
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
        $deletedOvertime = $this->overtime->deleteOvertime($id);

        return response()->json([
            'message'   =>  'Overtime Deleted'
        ], 200);
    }
}
