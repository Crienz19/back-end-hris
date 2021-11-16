<?php

namespace App\Http\Controllers\Api\Administrator;

use App\Http\Resources\Overtime\OvertimeResourceWithEmployeeAndActions;
use App\Overtime;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OvertimeController extends Controller
{

    public function getEmployeeOvertime()
    {
        $overtimes = $this->getOvertimeByRole('employee');
        return OvertimeResourceWithEmployeeAndActions::collection($overtimes->sortByDesc('created_at'));
    }

    public function getSupervisorOvertime()
    {
        $overtimes = $this->getOvertimeByRole('supervisor');
        return OvertimeResourceWithEmployeeAndActions::collection($overtimes->sortByDesc('created_at'));
    }

    public function filterSupervisorOvertime(Request $request)
    {
        $overtimes = $this->filterOvertime('supervisor', $request->input('date_from'), $request->input('date_to'), $request->input('status'));
        return OvertimeResourceWithEmployeeAndActions::collection($overtimes->sortByDesc('created_at'));
    }

    public function filterEmployeeOvertime(Request $request)
    {
        $overtimes = $this->filterOvertime('employee', $request->input('date_from'), $request->input('date_to'), $request->input('status'));
        return OvertimeResourceWithEmployeeAndActions::collection($overtimes->sortByDesc('created_at'));
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
