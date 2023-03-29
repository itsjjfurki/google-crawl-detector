<?php

namespace Itsjjfurki\GoogleCrawlDetector;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Itsjjfurki\GoogleCrawlDetector\Console\FetchGoogleCrawlerIps;
use Itsjjfurki\GoogleCrawlDetector\Http\Middleware\DetectGoogleCrawl;

class GoogleCrawlDetectorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'googlecrawldetector');
    }

    /**
     * Bootstrap services.
     */
    public function boot(Kernel $kernel): void
    {
        if (
            $kernel instanceof \Illuminate\Foundation\Http\Kernel &&
            array_key_exists('web', $kernel->getMiddlewareGroups())
        ) {
            $kernel->appendMiddlewareToGroup('web', DetectGoogleCrawl::class);
        }

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('googlecrawldetector.php'),
        ], 'config');

        $this->commands([
            FetchGoogleCrawlerIps::class,
        ]);

        $this->app->booted(function () {
            /** @var Schedule $schedule */
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('googlecrawlerips:fetch')->everyFourHours()->runInBackground();
        });
    }
}
