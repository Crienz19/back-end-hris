<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Resources\Trip\TripResource;
use App\Http\Resources\Trip\TripResourceWithUpdateDelete;
use App\Notifications\Employee\TripEmToHrNotification;
use App\Repositories\Trip\ITripRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;

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
        $trips = $this->trip->getTripBy(['user_id' => auth()->user()->id]);

        return TripResourceWithUpdateDelete::collection($trips);
    }

    public function store(Request $request)
    {
        $data = [
            'user_id'           =>  auth()->user()->id,
            'date_from'         =>  $request->input('date_from'),
            'date_to'           =>  $request->input('date_to'),
            'time_in'           =>  $request->input('time_in'),
            'time_out'          =>  $request->input('time_out'),
            'destination_from'  =>  $request->input('destination_from'),
            'destination_to'    =>  $request->input('destination_to'),
            'purpose'           =>  $request->input('purpose')
        ];

        $storedTrip = $this->trip->saveTrip($data);

        Notification::route('mail', 'rmergenio@ziptravel.com.ph')->notify(new TripEmToHrNotification($data));

        return new TripResource($storedTrip);
    }

    public function show($id)
    {
        $selectedTrip = $this->trip->getOneTrip(['id' => $id]);

        return new TripResource($selectedTrip);
    }

    public function update(Request $request, $id)
    {
        $data = [
            'date_from'         =>  $request->input('date_from'),
            'date_to'           =>  $request->input('date_to'),
            'time_in'           =>  $request->input('time_in'),
            'time_out'          =>  $request->input('time_out'),
            'destination_from'  =>  $request->input('destination_from'),
            'destination_to'    =>  $request->input('destination_to'),
            'purpose'           =>  $request->input('purpose')
        ];

        $updateTrip = $this->trip->updateTrip(['id' => $id], $data);

        return response()->json([
            'message'   =>  'Trip Updated'
        ], 200);
    }

    public function destroy($id)
    {
        $deleteTrip = $this->trip->deleteTrip($id);

        return response()->json([
            'message'   =>  'Trip Deleted'
        ], 200);
    }
}
