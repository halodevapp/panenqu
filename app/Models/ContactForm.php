<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactForm extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(ContactFormCategory::class, 'contact_category')->withDefault([
            'category_name' => ''
        ]);
    }
}
