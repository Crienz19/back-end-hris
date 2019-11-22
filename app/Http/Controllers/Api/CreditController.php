<?php

namespace App\Http\Controllers\Api;

use App\Credit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $credits = Credit::orderBy('created_at', 'desc')
            ->get()
            ->map
            ->format();

        return response()->json($credits);
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
        $credit = Credit::where('user_id', $id);
        $credit->update([
            'VL'        =>  $request->input('VL'),
            'SL'        =>  $request->input('SL'),
            'PTO'       =>  $request->input('PTO'),
            'total_PTO' =>  $request->input('total_PTO'),
            'total_SL'  =>  $request->input('total_SL'),
            'total_VL'  =>  $request->input('total_VL')
        ]);

        return response()->json($credit->first()->format());
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
}
