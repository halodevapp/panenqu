<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductHasStore extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id')->withDefault([
            'store_name' => ''
        ]);
    }
}
