# Google Crawl Detector

Google Crawl Detector is a Laravel package that enables logging each request made by Google. It is essential for websites that want to improve their SEO performance to log every request made by Google crawlers. Doing so allows webmasters to obtain valuable insights into how Google crawls and indexes their website. This information can be used to detect potential crawl errors, indexation problems, and areas where optimization is possible.

## Installation:

Fetch the package via composer:

```bash
composer require itsjjfurki/google-crawl-detector
```

Publish service provider:

```bash
php artisan vendor:publish --provider="Itsjjfurki\GoogleCrawlDetector\GoogleCrawlDetectorServiceProvider"
```

Migrate to create google_crawls table:

```bash
php artisan migrate
```

Modify and add this to your .env file:

```bash
GOOGLE_CRAWL_DETECTOR_ENABLED=true
```

For more information as to what environment variables you can modify, please check out the config file for this package.

Run the console command to fetch the Google crawler IPs:

```bash
php artisan googlecrawlerips:fetch
```

## Optional

In order to fetch Google crawler IPs regularly, create a cronjob to execute scheduled tasks.

## Contributing

Please make sure to update tests as appropriate.

Please make sure to write tests and run them as often as possible.

Please make sure to keep the tests fast (less than a second) as you will be running them often.

Please make sure you follow the project code style guides. It currently uses `Laravel Pint`'s default settings.

Please make sure you do all of the above before creating a pull request :)

In order to run the tests and make sure that the project code style guidelines are followed, you can run the following command:

```bash
composer check
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
