<?php

namespace App\Http\Controllers;

use App\Credit;
use App\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function getUserPersonalDetails()
    {
        return Employee::where('user_id', request()->user()->id)->first()->format();
    }

    public function getUserLeaveCredits()
    {
        return Credit::where('user_id', request()->user()->id)->first();
    }
}
