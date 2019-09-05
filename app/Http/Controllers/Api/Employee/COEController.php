<?php

namespace App\Http\Controllers\Api\Employee;

use App\COE;
use App\Http\Resources\COE\COEResource;
use App\Notifications\Employee\COEEmToHrNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;

class COEController extends Controller
{
    public function index()
    {
        $coe = COE::where('user_id', '=', auth()->user()->id)->get();

        return COEResource::collection($coe);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_needed'   =>  'required',
            'reason'        =>  'required',
            'compensation'  =>  'required'
        ]);

        $storedCOE = COE::create([
            'user_id'       =>  auth()->user()->id,
            'date_needed'   =>  $request->input('date_needed'),
            'reason'        =>  $request->input('reason'),
            'compensation'  =>  $request->input('compensation')
        ]);

        Notification::route('mail', 'rmergenio@ziptravel.com.ph')->notify(new COEEmToHrNotification($storedCOE));

        return response()->json([
            'message'   =>  'COE submitted!'
        ], 200);
    }

    public function show($id)
    {

    }

    public function update(Request $request, $id)
    {
        COE::find($id)->update($request->all());

        return response()->json([
            'message'   =>  'COE updated!'
        ]);
    }

    public function destroy($id)
    {
        COE::find($id)->delete();

        return response()->json([
            'message'   =>  'COE Deleted!'
        ], 200);
    }
}
