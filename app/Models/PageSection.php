<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function contents()
    {
        return $this->hasMany(PageContent::class, 'page_section');
    }
}
