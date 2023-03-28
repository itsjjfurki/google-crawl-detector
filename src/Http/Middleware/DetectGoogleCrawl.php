<?php

namespace Itsjjfurki\GoogleCrawlDetector\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Itsjjfurki\GoogleCrawlDetector\Models\GoogleCrawl;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class DetectGoogleCrawl
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): Response  $next
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cacheKey = (string) config('googlecrawldetector.cache_key');
        if (
            ! config('googlecrawldetector.enabled') ||
            ! cache()->has($cacheKey)
        ) {
            return $next($request);
        }

        /** @var array<string> $googlebotIps */
        $googlebotIps = json_decode((string) cache()->get($cacheKey));
        if (! in_array($request->ip(), $googlebotIps)) {
            return $next($request);
        }

        GoogleCrawl::create([
            'url' => $request->getRequestUri(),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
        ]);

        return $next($request);
    }
}
