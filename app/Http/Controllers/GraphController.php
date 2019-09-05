<?php

namespace App\Http\Controllers;

use App\Leave;
use Illuminate\Http\Request;

class GraphController extends Controller
{
    public function getLeaveSummary()
    {
        $type = ['VL', 'SL', 'PTO', 'VL-Half', 'SL-Half', 'PTO-Half'];
        $text = '';
        for ($i = 0; $i < count($type); $i++) {
            $text.= $i.',';
        }

        return $text;
    }
}
