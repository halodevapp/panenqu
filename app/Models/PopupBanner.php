<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PopupBanner extends Model
{
    use HasFactory, SoftDeletes;

    const MEDIA_CATEGORY = 'POPUP';

    protected $guarded = ['id'];

    public function image()
    {
        return $this->hasOne(Media::class, 'model_id', 'id')->where('model_category', self::MEDIA_CATEGORY);
    }
}
