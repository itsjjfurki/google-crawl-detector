<?php

namespace Itsjjfurki\GoogleCrawlDetector\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Itsjjfurki\GoogleCrawlDetector\Database\Factories\GoogleCrawlFactory;

/**
 * @method static GoogleCrawl create(array $attributes = [])
 */
class GoogleCrawl extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = ['url', 'user_agent', 'ip'];

    protected static function newFactory(): GoogleCrawlFactory
    {
        return GoogleCrawlFactory::new();
    }
}
