<?php

namespace App\Console;

use App\Services\DisciplinePerformanceDataService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        //$schedule->command('inspire')->everyMinute();
        $schedule->call(function(){
            $service = new DisciplinePerformanceDataService();
            Log::info("Rodando as tarefas agendadas");
            $service->runSchedules();
            Log::info("Terminou de rodar as tarefas agendadas");
        })->name("updateScheduling")
            ->withoutOverlapping()
            ->dailyAt('03:00')
            ->timezone('America/Sao_Paulo');
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
