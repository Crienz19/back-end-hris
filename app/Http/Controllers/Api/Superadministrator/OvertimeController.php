<?php

namespace App\Http\Controllers\Api\Superadministrator;

use App\Repositories\Overtime\IOvertimeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Overtime;
use App\Http\Resources\Overtime\OvertimeResourceWithEmployeeDetails;
use App\Http\Resources\Overtime\OvertimeResource;

class OvertimeController extends Controller
{
    private $overtime;
    public function __construct(IOvertimeRepository $overtimeRepository)
    {
        $this->overtime = $overtimeRepository;
    }

    public function index()
    {
        $overtimes = $this->overtime->allOvertimes();

        return OvertimeResourceWithEmployeeDetails::collection($overtimes->sortByDesc('created_at'));
    }

    public function store(Request $request)
    {
        $storedOvertime = $this->overtime->saveOvertime($request->all());

        return new OvertimeResource($storedOvertime);
    }

    public function show($id)
    {
        $selectedOvertime = $this->overtime->getOneOvertime(['id' => $id]);

        return new OvertimeResourceWithEmployeeDetails($selectedOvertime);
    }

    public function update(Request $request, $id)
    {
        $updatedOvertime = $this->overtime->updateOvertime(['id' => $id], [
            'date'          =>  $request->input('date'),
            'from'          =>  $request->input('from'),
            'to'            =>  $request->input('to'),
            'reason'        =>  $request->input('reason'),
            'status'        =>  $request->input('status')
        ]);

        return new OvertimeResourceWithEmployeeDetails($this->overtime->getOneOvertime(['id' => $id]));
    }

    public function destroy($id)
    {
        $deletedOvertime = $this->overtime->deleteOvertime($id);

        return response()->json([
            'message'   =>  'Overtime Deleted'
        ], 200);
    }
}
