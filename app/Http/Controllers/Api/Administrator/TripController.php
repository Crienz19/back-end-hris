<?php

namespace App\Http\Controllers\Api\Administrator;

use App\Http\Resources\Trip\TripResourceWithEmployeeAndActions;
use App\Trip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TripController extends Controller
{
    public function getEmployeeTrip()
    {
        return TripResourceWithEmployeeAndActions::collection($this->getTripByRole('employee'));
    }

    public function getSupervisorTrip()
    {
        return TripResourceWithEmployeeAndActions::collection($this->getTripByRole('supervisor'));
    }

    private function getTripByRole($role)
    {
        $trips = Trip::join('users', 'trips.user_id', '=', 'users.id')
                        ->where('users.role', '=', $role)
                        ->select('trips.*')
                        ->get();
        return $trips;
    }
}
