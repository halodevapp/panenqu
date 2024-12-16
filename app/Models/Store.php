<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ["id"];

    const STORE_OFFLINE = 1;
    const STORE_ONLINE = 2;

    const MEDIA_STORE_ONLINE = 'STORE_ONLINE';
    const MEDIA_STORE_OFFLINE = 'STORE_OFFLINE';

    public function StoreType()
    {
        return $this->belongsTo(StoreType::class, 'store_type_id')->withDefault([
            'type_name' => ''
        ]);
    }

    public function storeImageOnline()
    {
        return $this->hasMany(Media::class, 'model_id', 'id')->where('model_category', self::MEDIA_STORE_ONLINE);
    }

    public function product()
    {
        return $this->hasOne(ProductHasStore::class, 'store_id')->withDefault([
            'product_store_link' => ''
        ]);
    }
}
