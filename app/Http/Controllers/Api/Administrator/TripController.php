<?php

namespace App\Http\Controllers\Api\Administrator;

use App\Http\Resources\Trip\TripResourceWithEmployeeAndActions;
use App\Trip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TripController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getEmployeeTrip()
    {
        return TripResourceWithEmployeeAndActions::collection($this->getTripByRole('employee'));
    }

    public function getSupervisorTrip()
    {
        return TripResourceWithEmployeeAndActions::collection($this->getTripByRole('supervisor'));
    }

    public function filterSupervisorTrip(Request $request)
    {
        $trips = $this->filterTrip('supervisor', $request->input('date_from'), $request->input('date_to'), $request->input('status'));

        return TripResourceWithEmployeeAndActions::collection($trips);
    }

    public function filterEmployeeTrip(Request $request)
    {
        $trips = $this->filterTrip('employee', $request->input('date_from'), $request->input('date_to'), $request->input('status'));

        return TripResourceWithEmployeeAndActions::collection($trips);
    }

    private function getTripByRole($role)
    {
        $trips = Trip::join('users', 'trips.user_id', '=', 'users.id')
                        ->where('users.role', '=', $role)
                        ->select('trips.*')
                        ->get();
        return $trips;
    }

    private function filterTrip($role, $date_from, $date_to, $status)
    {
        $leaves = Trip::join('users', 'trips.user_id', '=', 'users.id')
                    ->where('users.role', '=', $role)
                    ->where('trips.status', '=', $status)
                    ->whereBetween('trips.created_at', [$date_from, $date_to])
                    ->get();

        return TripResourceWithEmployeeAndActions::collection($leaves);
    }
}
