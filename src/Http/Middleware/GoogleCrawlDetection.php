<?php

namespace Itsjjfurki\GoogleCrawlDetector\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Itsjjfurki\GoogleCrawlDetector\Models\GoogleCrawl;
use Symfony\Component\HttpFoundation\Response;

class GoogleCrawlDetection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('googlecrawldetector.enabled') && Cache::has('googleCrawlerIpList')) {
            $googlebot_ips = json_decode(Cache::get('googleCrawlerIpList'));

            if (in_array($request->ip(), $googlebot_ips)) {
                GoogleCrawl::create([
                    'url' => $request->getRequestUri(),
                    'user_agent' => $request->userAgent(),
                    'ip' => $request->ip(),
                ]);
            }
        }

        return $next($request);
    }
}
