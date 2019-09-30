<?php

namespace App\Http\Controllers\Api\HumanResource;

use App\Http\Resources\Overtime\OvertimeResource;
use App\Http\Resources\Overtime\OvertimeResourceWithEmployeeAndActions;
use App\Repositories\Overtime\IOvertimeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OvertimeController extends Controller
{
    private $overtime;
    public function __construct(IOvertimeRepository $overtimeRepository)
    {
        $this->middleware('auth:api');
        $this->overtime = $overtimeRepository;
    }

    public function index()
    {
        $overtimes = $this->overtime->allOvertimes();

        return OvertimeResourceWithEmployeeAndActions::collection($overtimes->sortByDesc('created_at'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $storedOvertime = $this->overtime->saveOvertime($request->all());

        return new OvertimeResource($storedOvertime);
    }

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
        $updatedOvertime = $this->overtime->updateOvertime(['id' => $id], $request->all());

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
        //
    }

    public function approve($id)
    {
        $this->overtime->approveOvertime($id);

        return response()->json([
            'message'   =>  'Overtime Approved'
        ], 200);
    }

    public function disapprove($id)
    {
        $this->overtime->disapproveOvertime($id);

        return response()->json([
            'message'   =>  'Overtime Disapproved'
        ], 200);
    }

    public function filterOvertime(Request $request)
    {
        $overtimes = $this->overtime->filterOvertime($request->input('date_from'), $request->input('date_to'), $request->input('status'));
        return OvertimeResourceWithEmployeeAndActions::collection($overtimes);
    }
}
