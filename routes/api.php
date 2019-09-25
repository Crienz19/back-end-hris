<?php

Route::prefix('auth')->group(function () {
    Route::post('/register', 'Api\AuthController@register');
    Route::post('/login', 'Api\AuthController@login');
    Route::post('/logout', 'Api\AuthController@logout');
    Route::get('/me', 'Api\AuthController@me');
});

Route::post('/users/changePassword', 'Api\UserController@changePassword')->name('users.changePassword');
Route::get('/employees/registered', 'Api\EmployeeController@registeredEmployees')->name('employees.registered');
Route::post('/employees/photo', 'Api\EmployeeController@updateImage')->name('employees.photo');

Route::apiResources([
    'users'         =>  'Api\UserController',
    'employees'     =>  'Api\EmployeeController',
    'departments'   =>  'Api\DepartmentController',
    'branches'      =>  'Api\BranchController',
    'roles'         =>  'Api\RoleController',
]);

Route::prefix('sa')->group(function () {
    Route::apiResources([
        'leaves'        =>  'Api\Superadministrator\LeaveController',
        'overtimes'     =>  'Api\Superadministrator\OvertimeController',
        'trips'         =>  'Api\Superadministrator\TripController',
    ]);
});

Route::prefix('hr')->name('hr.')->group(function () {
    Route::post('/leaves/filter', 'Api\HumanResource\LeaveController@filterLeave')->name('leaves.filter');
    Route::patch('/leaves/{id}/approved', 'Api\HumanResource\LeaveController@approve')->name('leaves.approve');
    Route::patch('/leaves/{id}/disapproved', 'Api\HumanResource\LeaveController@disapprove')->name('leaves.disapprove');

    Route::post('/overtimes/filter', 'Api\HumanResource\OvertimeController@filterOvertime')->name('overtimes.filter');
    Route::patch('/overtimes/{id}/approved', 'Api\HumanResource\OvertimeController@approve')->name('overtimes.approve');
    Route::patch('/overtimes/{id}/disapproved', 'Api\HumanResource\OvertimeController@disapprove')->name('overtimes.disapprove');

    Route::post('/trips/filter', 'Api\HumanResource\TripController@filterTrip')->name('trips.filter');
    Route::patch('trips/{id}/acknowledged', 'Api\HumanResource\TripController@acknowledge')->name('trips.acknowledge');

    Route::get('/coes', 'Api\HumanResource\COEController@index')->name('coes.index');
    Route::patch('/coes/{id}', 'Api\HumanResource\COEController@acknowledged')->name('coes.acknowledge');

    Route::apiResources([
        'leaves'        =>  'Api\HumanResource\LeaveController',
        'overtimes'     =>  'Api\HumanResource\OvertimeController',
        'trips'         =>  'Api\HumanResource\TripController'
    ]);
});

Route::prefix('sv')->name('sv.')->group(function () {
    Route::patch('/leaves/{id}/approved', 'Api\Supervisor\LeaveController@approve')->name('leaves.approve');
    Route::patch('/leaves/{id}/disapproved', 'Api\Supervisor\LeaveController@disapprove')->name('leaves.disapprove');

    Route::patch('/overtimes/{id}/approved', 'Api\Supervisor\OvertimeController@approve')->name('overtimes.approve');
    Route::patch('/overtimes/{id}/disapproved', 'Api\Supervisor\OvertimeController@disapprove')->name('overtimes.disapprove');
    Route::apiResources([
       'leaves'         =>  'Api\Supervisor\LeaveController',
       'overtimes'      =>  'Api\Supervisor\OvertimeController',
       'trips'          =>  'Api\Supervisor\TripController'
    ]);
});

Route::prefix('em')->name('em.')->group(function () {
    Route::apiResources([
        'employees'     =>  'Api\Employee\EmployeeController',
        'leaves'        =>  'Api\Employee\LeaveController',
        'overtimes'     =>  'Api\Employee\OvertimeController',
        'trips'         =>  'Api\Employee\TripController',
        'coes'          =>  'Api\Employee\COEController'
    ]);
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/leaves/getEmployee', 'Api\Administrator\LeaveController@getEmployeeLeave')->name('leaves.getEmployee');
    Route::get('/leaves/getSupervisor', 'Api\Administrator\LeaveController@getSupervisorLeave')->name('leaves.getSupervisor');
    Route::patch('/leaves/{id}/approved', 'Api\Administrator\LeaveController@approveSupervisorLeave')->name('leaves.supervisor.approve');
    Route::patch('/leaves/{id}/disapproved', 'Api\Administrator\LeaveController@disapproveSupervisorLeave')->name('leaves.supervisor.disapprove');
    Route::post('/leaves/supervisor/filter', 'Api\Administrator\LeaveController@filterSupervisorLeave')->name('leaves.supervisor.filter');
    Route::post('/leaves/employee/filter', 'Api\Administrator\LeaveController@filterEmployeeLeave')->name('leaves.employee.filter');

    Route::get('/overtimes/getEmployee', 'Api\Administrator\OvertimeController@getEmployeeOvertime')->name('overtimes.getEmployee');
    Route::get('/overtimes/getSupervisor', 'Api\Administrator\OvertimeController@getSupervisorOvertime')->name('overtimes.getSupervisor');
    Route::post('/overtimes/supervisor/filter', 'Api\Administrator\OvertimeController@filterSupervisorOvertime')->name('overtimes.supervisor.filter');
    Route::post('/overtimes/employee/filter', 'Api\Administrator\OvertimeController@filterEmployeeOvertime')->name('overtimes.employee.fitler');

    Route::get('/trips/getEmployee', 'Api\Administrator\TripController@getEmployeeTrip')->name('trips.getEmployee');
    Route::get('/trips/getSupervisor', 'Api\Administrator\TripController@getSupervisorTrip')->name('trips.getSupervisor');
    Route::post('/trips/supervisor/filter', 'Api\Administrator\TripController@filterSupervisorTrip')->name('trips.supervisor.filter');
    Route::post('/trips/employee/filter', 'Api\Administrator\TripController@filterEmployeeTrip')->name('trips.employee.filter');
});