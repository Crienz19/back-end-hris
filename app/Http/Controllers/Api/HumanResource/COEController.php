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

        return COEResourceWithEmployeeDetailsAndActions::collection($coe->sortByDesc('created_at'));
    }

    public function acknowledged($id)
    {
        $coe = COE::find($id);

        $coe->update([
            'status'    =>  'Acknowledged'
        ]);

        

        return response()->json([
            'message'   =>  'COE Request Acknowledged!'
        ], 200);
    }
}
