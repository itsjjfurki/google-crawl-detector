<?php

return [

    'enabled' => (bool) env('GOOGLE_CRAWL_DETECTOR_ENABLED', false),
    'cache_key' => (string) env('GOOGLE_CRAWL_DETECTOR_CACHE_KEY', 'googleCrawlerIpList'),
    'googlebot_url' => (string) env('GOOGLE_CRAWL_DETECTOR_GOOGLEBOT_URL', 'https://developers.google.com/static/search/apis/ipranges/googlebot.json'),

];
