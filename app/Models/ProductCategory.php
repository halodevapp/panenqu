<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ProductCategory extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $guarded = ['id'];

    const MEDIA_CATEGORY = 'PRODUCT_CATEGORY';

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('category_name')
            ->saveSlugsTo('category_slug')
            ->slugsShouldBeNoLongerThan(255);
    }

    public function images()
    {
        return $this->hasMany(Media::class, 'model_id', 'id')->where('model_category', self::MEDIA_CATEGORY);
    }
}
