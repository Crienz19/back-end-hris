<?php

namespace App\Http\Controllers\Api\HumanResource;

use App\COE;
use App\Http\Resources\COE\COEResourceWithEmployeeDetailsAndActions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class COEController extends Controller
{
    public function index()
    {
        $coe = COE::all();

        return COEResourceWithEmployeeDetailsAndActions::collection($coe);
    }

    public function acknowledged($id)
    {
        COE::find($id)->update([
            'status'    =>  'Acknowledged'
        ]);

        return response()->json([
            'message'   =>  'COE Request Acknowledged!'
        ], 200);
    }
}
