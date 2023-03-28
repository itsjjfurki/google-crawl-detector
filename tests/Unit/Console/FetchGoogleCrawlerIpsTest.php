<?php

namespace Itsjjfurki\GoogleCrawlDetector\Tests\Unit\Console;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Promise\RejectedPromise;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Itsjjfurki\GoogleCrawlDetector\Services\IPv4CIDRService;
use Itsjjfurki\GoogleCrawlDetector\Tests\TestCase;

class FetchGoogleCrawlerIpsTest extends TestCase
{
    public function test_should_fetch_google_crawler_ips()
    {
        // Mock the Http facade to return a response with a JSON payload
        $responseJson = '{"prefixes":[{"ipv4Prefix": "1.2.3.4/24"}, {"ipv6Prefix": "2001:4860:4801:11::/64"}, {"ipv4Prefix": "5.6.7.8/16"}]}';
        Http::fake([
            config('googlecrawldetector.googlebot_url') => Http::response($responseJson, 200),
        ]);

        // Mock the IPv4CIDRService to return some IP addresses
        $service = $this->mock(IPv4CIDRService::class);
        $service->shouldReceive('resolveCIDR')->with('1.2.3.4/24')->once()->andReturn(['1.2.3.4', '1.2.3.5']);
        $service->shouldReceive('resolveCIDR')->with('5.6.7.8/16')->once()->andReturn(['5.6.0.1', '5.6.255.255']);

        // Run the command and assert the cache was updated with the fetched IPs
        $this->artisan('googlecrawlerips:fetch')->expectsOutput('Google crawler IPs fetched successfully!');
        $this->assertEquals(json_encode(['1.2.3.4', '1.2.3.5', '5.6.0.1', '5.6.255.255']), cache(config('googlecrawldetector.cache_key')));
    }

    public function test_should_handle_exceptions()
    {
        $url = config('googlecrawldetector.googlebot_url');
        $message = 'Connection error, please try again later.';

        // Mock the Http facade to return a 404 response
        Http::fake([
            $url => fn (Request $request) => new RejectedPromise(
                new ConnectException($message, new \GuzzleHttp\Psr7\Request('GET', $url))
            ),
        ]);

        // Run the command and assert the error message is displayed
        $this->artisan('googlecrawlerips:fetch')
            ->expectsOutput('Oops! Something went wrong.')
            ->expectsOutput($message);
    }
}
