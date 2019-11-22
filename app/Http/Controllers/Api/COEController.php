<?php

namespace App\Http\Controllers\Api;

use App\COE;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class COEController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coes = COE::orderBy('created_at', 'desc')
            ->get()
            ->map
            ->format();

        return response()->json($coes);
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
            'date_needed'   =>  ['required'],
            'reason'        =>  ['required'],
            'compensation'  =>  ['required']
        ]);

        $coe = auth()->user()->coe()->create([
            'date_needed'   =>  $request->input('date_needed'),
            'reason'        =>  $request->input('reason'),
            'compensation'  =>  $request->input('compensation'),
            'status'        =>  'Pending'
        ]);

        return response()->json($coe);
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
        $request->validate([
            'date_needed'   =>  ['required'],
            'reason'        =>  ['required'],
            'compensation'  =>  ['required']
        ]);

        $coe = COE::where('id', $id);
        $coe->update([
            'date_needed'   =>  $request->has('date_needed') ? $request->input('date_needed') : $coe->first()->date_needed,
            'reason'        =>  $request->has('reason') ? $request->input('reason') : $coe->first()->reason,
            'compensation'  =>  $request->has('compensation') ? $request->input('compensation') : $coe->first()->compensation
        ]);

        return response()->json($coe->first()->format());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        COE::where('id', $id)->delete();

        return response()->json($id);
    }

    public function acknowledgeCoe($id)
    {
        $coe = COE::where('id', $id);
        $coe->update([
            'status'    =>  'Acknowledged'
        ]);



        return response()->json($coe->first()->format());
    }
}
