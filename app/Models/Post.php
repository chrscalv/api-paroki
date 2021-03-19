<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use \Spatie\Tags\HasTags;
use \Conner\Tagging\Taggable;

class Post extends Model
{
    use HasFactory, HasSlug,Taggable;

    protected $fillable = [
        'title',
        'slug',
        'body',
        'category_id',
        'is_published',
        'published_at',
        'user_id'
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Category()
    {
        return $this->belongsTo(Category::class);
    }
}
