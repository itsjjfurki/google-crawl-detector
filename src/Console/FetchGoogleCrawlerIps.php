<?php

namespace Itsjjfurki\GoogleCrawlDetector\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

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
    public function handle(): void
    {
        $google_ipranges_url = 'https://developers.google.com/static/search/apis/ipranges/googlebot.json';
        $ipv4Label = 'ipv4Prefix';

        try {
            $response = Http::get($google_ipranges_url);

            $ip_list = json_decode($response->body());

            $resolved_ip_list = [];

            foreach ($ip_list->prefixes as $ip_range) {
                if (property_exists($ip_range, $ipv4Label)) {
                    $ip_cidr = (string) $ip_range->{$ipv4Label};
                    $range = $this->cidr_to_range($ip_cidr);
                    $start_ip = ip2long($range[0]);
                    $end_ip = ip2long($range[1]);
                    for ($i = $start_ip; $i <= $end_ip; $i++) {
                        $resolved_ip_list[] = long2ip($i);
                    }
                }
            }

            Cache::forever('googleCrawlerIpList', json_encode($resolved_ip_list));

            $this->info("Google Crawler IP's fetched!");
        } catch (\Exception $e) {
            $this->error('googlecrawlerips:fetch command failed!');
        }
    }

    private function cidr_to_range($cidr)
    {
        $range = [];
        $cidr = explode('/', $cidr);
        $range[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int) $cidr[1]))));
        $range[1] = long2ip((ip2long($range[0])) + pow(2, (32 - (int) $cidr[1])) - 1);

        return $range;
    }
}
