<?php

namespace App\Http\Controllers\Api\Administrator;

use App\Http\Resources\Overtime\OvertimeResourceWithEmployeeAndActions;
use App\Overtime;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OvertimeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getEmployeeOvertime()
    {
        return OvertimeResourceWithEmployeeAndActions::collection($this->getOvertimeByRole('employee'));
    }

    public function getSupervisorOvertime()
    {
        return OvertimeResourceWithEmployeeAndActions::collection($this->getOvertimeByRole('supervisor'));
    }

    public function filterSupervisorOvertime(Request $request)
    {
        $overtimes = $this->filterOvertime('supervisor', $request->input('date_from'), $request->input('date_to'), $request->input('status'));
        return OvertimeResourceWithEmployeeAndActions::collection($overtimes);
    }

    public function filterEmployeeOvertime(Request $request)
    {
        $overtimes = $this->filterOvertime('employee', $request->input('date_from'), $request->input('date_to'), $request->input('status'));
        return OvertimeResourceWithEmployeeAndActions::collection($overtimes);
    }

    private function getOvertimeByRole($role)
    {
        $overtimes = Overtime::join('users', 'overtimes.user_id', '=', 'users.id')
                        ->where('users.role', '=', $role)
                        ->select('overtimes.*')
                        ->get();

        return $overtimes;
    }

    private function filterOvertime($role, $date_from, $date_to, $status)
    {
        $overtimes = Overtime::join('users', 'overtimes.user_id', '=', 'users.id')
                            ->where('users.role', '=', $role)
                            ->where('overtimes.status', '=', $status)
                            ->whereBetween('overtimes.created_at', [$date_from, $date_to])
                            ->get();

        return OvertimeResourceWithEmployeeAndActions::collection($overtimes);
    }
}
