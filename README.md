# Google Crawl Detector

Google Crawl Detector is a Laravel package that enables logging each request made by Google.

## Installation:

Fetch the package via composer:

```php
composer require itsjjfurki/google-crawl-detector
```

Publish service provider:

```php
php artisan vendor:publish --provider="Itsjjfurki\GoogleCrawlDetector\GoogleCrawlDetectorServiceProvider"
```

Migrate to create google_crawls table:

```php
php artisan migrate
```

Modify and add this to your .env file:

```php
GOOGLE_CRAWL_DETECTOR_ENABLED=TRUE
```

Run the console command:

```php
php artisan googlecrawlerips:fetch
```

## Optional

In order to fetch Google Crawler Ip's regularly, create a cronjob to execute scheduled tasks.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
