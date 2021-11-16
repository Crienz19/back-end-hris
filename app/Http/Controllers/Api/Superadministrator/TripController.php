<?php

namespace App\Http\Controllers\Api\Superadministrator;

use App\Repositories\Trip\ITripRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Trip;
use App\Http\Resources\Trip\TripResourceWithEmployeeDetails;
use App\Http\Resources\Trip\TripResource;

class TripController extends Controller
{
    private $trip;
    public function __construct(ITripRepository $tripRepository)
    {
        $this->trip = $tripRepository;
    }

    public function index()
    {
        $trips = $this->trip->allTrips();

        return TripResourceWithEmployeeDetails::collection($trips->sortByDesc('created_at'));
    }

    public function store(Request $request)
    {
        $storedTrip = $this->trip->saveTrip($request->all());

        return new TripResource($storedTrip);
    }

    public function show($id)
    {
        $selectedTrip = $this->trip->getOneTrip(['id' => $id]);

        return new TripResourceWithEmployeeDetails($selectedTrip);
    }

    public function showAllByUser($id)
    {
        $trips = $this->trip->getTripBy(['id' => $id]);

        return TripResource::collection($trips);
    }

    public function update(Request $request, $id)
    {
        $updatedTrip = $this->trip->updateTrip(['id' => $id], [
            'date_from'             =>  $request->input('date_from'),
            'date_to'               =>  $request->input('date_to'),
            'time_in'               =>  $request->input('time_in')['other'],
            'time_out'              =>  $request->input('time_out')['other'],
            'destination_from'      =>  $request->input('destination_from'),
            'destination_to'        =>  $request->input('destination_to'),
            'purpose'               =>  $request->input('purpose'),
            'status'                =>  $request->input('status')
        ]);

        return new TripResourceWithEmployeeDetails($this->trip->getOneTrip(['id' => $id]));
    }

    public function destroy($id)
    {
        $this->trip->deleteTrip($id);

        return response()->json([
            'message'   =>  'Trip Deleted'
        ], 200);
    }
}
