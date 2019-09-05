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
        $this->middleware('auth:api');
        $this->trip = $tripRepository;
    }

    public function index()
    {
        $trips = $this->trip->allTrips();

        return TripResourceWithEmployeeDetails::collection($trips);
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
        $updatedTrip = $this->trip->updateTrip(['id' => $id], $request->all());

        return response()->json([
            'message'   =>  'Trip Updated'
        ], 200);
    }

    public function destroy($id)
    {
        $this->trip->deleteTrip($id);

        return response()->json([
            'message'   =>  'Trip Deleted'
        ], 200);
    }
}
