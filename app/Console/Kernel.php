<?php

namespace App\Console;

use App\Employee;
use App\Notifications\LeaveCreditUpdateNotification;
use App\Notifications\NoUpdateNotification;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Notification;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->call(function () {
            $employee = \App\Employee::all();
            $employee->map(function ($item) {
                if (date('m-d', strtotime($item['date_hired'])) == date('m-d')) {
                    $credit = \App\Credit::where('user_id', $item['user_id']);

                    $credit->update([
                        'VL'    =>  (int) $credit->first()->total_VL + 1,
                        'SL'    =>  (int) $credit->first()->total_SL + 1,
                        'PTO'   =>  (int) $credit->first()->VL + (int) $credit->first()->SL,
                        'total_VL'  =>  (int) $credit->first()->total_VL + 1,
                        'total_SL'  =  >  (int) $credit->first()->total_SL + 1,
                        'total_PTO' =>  (int) $credit->first()->VL + (int) $credit->first()->SL
                    ]);

                    Notification::route('mail', env('SUPERADMIN_EMAIL'))->notify(new LeaveCreditUpdateNotification($item));
                }
            });
        })->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
