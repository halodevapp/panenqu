<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    const MEDIA_CATEGORY = 'PRODUCT';

    protected $guarded = ['id'];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('product_name')
            ->saveSlugsTo('product_slug')
            ->slugsShouldBeNoLongerThan(255);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category')->withDefault([
            'category_name' => ''
        ]);
    }

    public function images()
    {
        return $this->hasMany(Media::class, 'model_id', 'id')->where('model_category', self::MEDIA_CATEGORY)->orderBy('id', 'DESC');
    }

    public function stores()
    {
        return $this->hasMany(ProductHasStore::class, 'product_id');
    }

    public function related()
    {
        return $this->hasMany(Product::class, 'product_category', 'product_category')->limit(4)->orderBy('id', 'desc');
    }
}
