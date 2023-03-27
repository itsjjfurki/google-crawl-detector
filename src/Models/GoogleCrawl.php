<?php

namespace Itsjjfurki\GoogleCrawlDetector\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleCrawl extends Model
{
    protected $guarded = [];

    protected $fillable = ['url', 'user_agent', 'ip'];
}
