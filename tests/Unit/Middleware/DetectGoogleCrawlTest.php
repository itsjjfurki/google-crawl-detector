<?php

namespace Itsjjfurki\GoogleCrawlDetector\Tests\Unit\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Itsjjfurki\GoogleCrawlDetector\Http\Middleware\DetectGoogleCrawl;
use Itsjjfurki\GoogleCrawlDetector\Tests\TestCase;

class DetectGoogleCrawlTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_call_next_when_feature_is_disabled(): void
    {
        config()->set('googlecrawldetector.enabled', false);
        cache()->set(config('googlecrawldetector.cache_key'), json_encode([]));

        $request = Request::create('/some-place/on/the-website');

        $response = (new DetectGoogleCrawl())->handle($request, function () {
            return new Response();
        });

        $this->assertInstanceOf(Response::class, $response);
        $this->assertDatabaseCount('google_crawls', 0);
    }

    public function test_should_call_next_when_cache_is_not_found(): void
    {
        config()->set('googlecrawldetector.enabled', true);

        $request = Request::create('/some-place/on/the-website');

        $response = (new DetectGoogleCrawl())->handle($request, function () {
            return new Response();
        });

        $this->assertInstanceOf(Response::class, $response);
        $this->assertDatabaseCount('google_crawls', 0);
    }

    public function test_should_call_next_when_request_ip_is_not_found_in_cache(): void
    {
        config()->set('googlecrawldetector.enabled', true);
        cache()->set(config('googlecrawldetector.cache_key'), json_encode(['140.151.183.216']));

        $request = Request::create('/some-place/on/the-website', 'GET', [], [], [], [
            'REMOTE_ADDR' => '245.108.222.0',
        ]);

        $response = (new DetectGoogleCrawl())->handle($request, function () {
            return new Response();
        });

        $this->assertInstanceOf(Response::class, $response);
        $this->assertDatabaseCount('google_crawls', 0);
    }

    public function test_should_insert_record_in_db_when_request_ip_is_found_in_cache(): void
    {
        $ip = '140.151.183.216';

        config()->set('googlecrawldetector.enabled', true);
        cache()->set(config('googlecrawldetector.cache_key'), json_encode([$ip]));

        $request = Request::create('/some-place/on/the-website', 'GET', [], [], [], [
            'REMOTE_ADDR' => $ip,
            'User-Agent' => 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_8_8)  AppleWebKit/536.0.2 (KHTML, like Gecko) Chrome/27.0.849.0 Safari/536.0.2',
        ]);

        $response = (new DetectGoogleCrawl())->handle($request, function () {
            return new Response();
        });

        $this->assertInstanceOf(Response::class, $response);
        $this->assertDatabaseCount('google_crawls', 1);
        $this->assertDatabaseHas('google_crawls', [
            'url' => $request->getRequestUri(),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
        ]);
    }
}
