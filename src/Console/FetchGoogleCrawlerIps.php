<?php

namespace Itsjjfurki\GoogleCrawlDetector\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Itsjjfurki\GoogleCrawlDetector\Services\IPv4CIDRService;
use stdClass;

class FetchGoogleCrawlerIps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'googlecrawlerips:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Google crawlers IP addresses';

    /**
     * Execute the console command.
     */
    public function handle(IPv4CIDRService $service): void
    {
        try {
            $addresses = [];
            foreach ($this->fetchIPv4CIDRsFromGoogle() as $cidr) {
                $addresses = array_merge($addresses, $service->resolveCIDR($cidr));
            }
            cache()->forever((string) config('googlecrawldetector.cache_key'), json_encode($addresses));
            $this->info('Google crawler IPs fetched successfully!');
        } catch (\Exception $e) {
            $this->error('Oops! Something went wrong.');
            $this->error($e->getMessage());
        }
    }

    /**
     * Fetches IPv4 CIDRs from Google's IP address list API endpoint.
     *
     * @return string[] An array of IPv4 CIDRs retrieved from the Google API endpoint.
     */
    protected function fetchIPv4CIDRsFromGoogle(): array
    {
        $response = Http::get((string) config('googlecrawldetector.googlebot_url'));

        /** @var stdClass $body */
        $body = json_decode($response->body()) ?? new stdClass();
        if (! property_exists($body, 'prefixes')) {
            return [];
        }

        /** @var stdClass[] $prefixes */
        $prefixes = $body->prefixes ?? [];
        $prefixes = array_filter($prefixes, fn (stdClass $prefix) => property_exists($prefix, 'ipv4Prefix'));

        return array_map(fn (stdClass $prefix) => (string) $prefix->ipv4Prefix, $prefixes);
    }
}
