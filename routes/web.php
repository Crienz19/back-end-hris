<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $supervisorEmail = \App\Employee::join('departments', 'employees.department_id', '=', 'departments.id')
        ->join('users', 'users.id', '=', 'departments.supervisor_id')
        ->where('employees.user_id', 5)
        ->select('users.email')
        ->first();
    return $supervisorEmail;
});