<?php

namespace Modules\ContentProcessing\Models;

use Illuminate\Database\Eloquent\Model;

class ScrapedContent extends Model
{

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'content',
        'content_hash',
        'author',
        'categories',
        'tags',
        'status',
        'domain',
        'url',
        'errors',
        'published_at',
    ];
}
