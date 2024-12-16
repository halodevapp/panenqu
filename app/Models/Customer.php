<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    const MEDIA_CATEGORY = 'CUSTOMER';

    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(CustomerCategory::class, 'customer_category')->withDefault([
            'customer_name' => ''
        ]);
    }

    public function images()
    {
        return $this->hasMany(Media::class, 'model_id', 'id')->where('model_category', self::MEDIA_CATEGORY);
    }
}
