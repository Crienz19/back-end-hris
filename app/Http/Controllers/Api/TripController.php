<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notifications\Employee\TripEmToHrNotification;
use App\Notifications\HumanResource\TripApproveNotification;
use App\Trip;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trips = Trip::orderBy('created_at', 'desc')
            ->get()
            ->map
            ->format();

        return response()->json($trips);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'date_from'         =>  ['required'],
            'date_to'           =>  ['required'],
            'time_in'           =>  ['required'],
            'time_out'          =>  ['required'],
            'destination_from'  =>  ['required'],
            'destination_to'    =>  ['required'],
            'purpose'           =>  ['required'],
        ]);

        $data = [
            'date_from'         =>  $request->input('date_from'),
            'date_to'           =>  $request->input('date_to'),
            'time_in'           =>  $request->input('time_in'),
            'time_out'          =>  $request->input('time_out'),
            'destination_from'  =>  $request->input('destination_from'),
            'destination_to'    =>  $request->input('destination_to'),
            'purpose'           =>  $request->input('purpose'),
            'status'            =>  'Pending'
        ];

        $trip = auth()->user()->trip()->create($data);

        //Notification::route('mail', env('HR_EMAIL'))->notify(new TripEmToHrNotification($data));

        return response()->json($trip->format());
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
        $data = [
            'date_from'         =>  $request->input('date_from'),
            'date_to'           =>  $request->input('date_to'),
            'time_in'           =>  $request->input('time_in'),
            'time_out'          =>  $request->input('time_out'),
            'destination_from'  =>  $request->input('destination_from'),
            'destination_to'    =>  $request->input('destination_to'),
            'purpose'           =>  $request->input('purpose')
        ];

        $trip = Trip::where('id', $id);
        $trip->update($data);

        return response()->json($trip->first()->format());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trip = Trip::where('id', $id);
        $trip->delete();

        return response()->json($id);
    }

    public function acknowledgeTrip($id)
    {
        $trip = Trip::where('id', $id);
        $trip->update([
            'status'    =>  'Acknowledged'
        ]);

        Notification::route('mail', User::find($trip->first()->user_id)->email)->notify(new TripApproveNotification());

        return response()->json($trip->first()->format());
    }

    public function filterTrip(Request $request)
    {
        $trip = Trip::where('status', $request->input('status'))
            ->whereBetween('created_at', [$request->input('date_from'), $request->input('date_to')])
            ->get()
            ->map
            ->format();

        return response()->json($trip);
    }
}
