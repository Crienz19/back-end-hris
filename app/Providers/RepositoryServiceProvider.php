<?php

namespace App\Providers;

use App\Repositories\Branch\BranchRepository;
use App\Repositories\Branch\IBranchRepository;
use App\Repositories\Credit\CreditRepository;
use App\Repositories\Credit\ICreditRepository;
use App\Repositories\Department\DepartmentRepository;
use App\Repositories\Department\IDepartmentRepository;
use App\Repositories\Employee\EmployeeRepository;
use App\Repositories\Employee\IEmployeeRepository;
use App\Repositories\Leave\ILeaveRepository;
use App\Repositories\Leave\LeaveRepository;
use App\Repositories\Overtime\IOvertimeRepository;
use App\Repositories\Overtime\OvertimeRepository;
use App\Repositories\Role\IRoleRepository;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Trip\ITripRepository;
use App\Repositories\Trip\TripRepository;
use App\Repositories\User\IUserRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IEmployeeRepository::class, EmployeeRepository::class);
        $this->app->bind(ILeaveRepository::class, LeaveRepository::class);
        $this->app->bind(IOvertimeRepository::class, OvertimeRepository::class);
        $this->app->bind(ITripRepository::class, TripRepository::class);
        $this->app->bind(IBranchRepository::class, BranchRepository::class);
        $this->app->bind(IDepartmentRepository::class, DepartmentRepository::class);
        $this->app->bind(IRoleRepository::class, RoleRepository::class);
        $this->app->bind(ICreditRepository::class, CreditRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
