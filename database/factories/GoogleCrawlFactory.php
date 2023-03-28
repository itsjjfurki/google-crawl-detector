<?php

namespace Itsjjfurki\GoogleCrawlDetector\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Itsjjfurki\GoogleCrawlDetector\Models\GoogleCrawl;

class GoogleCrawlFactory extends Factory
{
    protected $model = GoogleCrawl::class;

    /**
     * {@inheritDoc}
     */
    public function definition()
    {
        return [
            'url' => $this->faker->url,
            'user_agent' => $this->faker->userAgent,
            'ip' => $this->faker->ipv4,
        ];
    }
}
