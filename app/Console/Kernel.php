<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
        Commands\OldChannelsImporter::class,
        Commands\OldSourcesImporter::class,
        Commands\PostsGetter::class,
        Commands\PostsUpdateScores::class,
        Commands\OldSourcesTrimmer::class,
        Commands\OldPostsDeleter::class,
        Commands\NewsSourcesAdder::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // get new posts every 15 minutes
        $schedule->command('posts:get')->cron('*/20 * * * *');

        // check scores every 10 minutes
        $schedule->command('posts:updateScores')->cron('*/5 * * * *');

        // delete old posts daily before midnight
        $schedule->command('posts:deleteOld')->dailyAt('23:50');
    }
}
