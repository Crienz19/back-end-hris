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
        return OvertimeResourceWithEmployeeAndActions::collection($this->getOvertimeByRole('employee'));
    }

    public function getSupervisorOvertime()
    {
        return OvertimeResourceWithEmployeeAndActions::collection($this->getOvertimeByRole('supervisor'));
    }

    private function getOvertimeByRole($role)
    {
        $overtimes = Overtime::join('users', 'overtimes.user_id', '=', 'users.id')
                        ->where('users.role', '=', $role)
                        ->select('overtimes.*')
                        ->get();

        return $overtimes;
    }
}
