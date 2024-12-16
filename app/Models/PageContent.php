<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageContent extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function group()
    {
        return $this->belongsTo(PageContentType::class, 'page_type');
    }

    public function section()
    {
        return $this->belongsTo(PageSection::class, 'page_section');
    }

    public function images()
    {
        return $this->hasMany(Media::class, 'model_id', 'id')->where('model_category', Page::MEDIA_CATEGORY)->orderBy('id', 'DESC');
    }
}
