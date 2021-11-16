<?php

namespace App\Http\Controllers\Api\HumanResource;

use App\Http\Resources\Trip\TripResourceWithEmployeeAndActions;
use App\Notifications\HumanResource\TripApproveNotification;
use App\Repositories\Trip\ITripRepository;
use App\Trip;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;

class TripController extends Controller
{
    private $trip;
    public function __construct(ITripRepository $tripRepository)
    {
        $this->trip = $tripRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leaves = $this->trip->allTrips();

        return TripResourceWithEmployeeAndActions::collection($leaves->sortByDesc('created_at'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        $updateTrip = $this->trip->updateTrip(['id' => $id], $request->all());

        return response()->json([
            'message'   =>  'Trip Updated!'
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

    public function acknowledge($id)
    {
        $trip = Trip::find($id);

        $trip->update([
            'status'    =>  'Acknowledged'
        ]);

        Notification::route('mail', User::find($trip->user_id)->email)->notify(new TripApproveNotification());

        return new TripResourceWithEmployeeAndActions($trip->first());
    }

    public function filterTrip(Request $request)
    {
        $leaves = $this->trip->filterTrip($request->input('date_from'), $request->input('date_to'), $request->input('status'));

        return TripResourceWithEmployeeAndActions::collection($leaves);
    }
}
