<?php

namespace Itsjjfurki\GoogleCrawlDetector;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Itsjjfurki\GoogleCrawlDetector\Console\FetchGoogleCrawlerIps;
use Itsjjfurki\GoogleCrawlDetector\Http\Middleware\GoogleCrawlDetection;

class GoogleCrawlDetectorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(Kernel $kernel)
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_google_crawls_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_google_crawls_table.php'),
            ], 'migrations');

            $this->publishes([
                __DIR__.'/../config/googlecrawldetector.php' => config_path('googlecrawldetector.php'),
            ]);

            $this->commands([
                FetchGoogleCrawlerIps::class,
            ]);

            $this->app->booted(function () {
                $schedule = $this->app->make(Schedule::class);
                $schedule->command('googlecrawlerips:fetch')->everyFourHours()->runInBackground();
            });
        }

        $kernel->appendMiddlewareToGroup('web', GoogleCrawlDetection::class);
    }
}
