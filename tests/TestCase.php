<?php

namespace Itsjjfurki\GoogleCrawlDetector\Tests;

use Itsjjfurki\GoogleCrawlDetector\GoogleCrawlDetectorServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected $enablesPackageDiscoveries = true;

    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            GoogleCrawlDetectorServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        //
    }
}
