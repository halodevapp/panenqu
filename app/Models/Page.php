<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const MEDIA_CATEGORY = 'PAGE_BANNER';

    public function sections()
    {
        return $this->hasMany(PageSection::class);
    }
}
