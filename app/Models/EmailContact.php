<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailContact extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function scopeDefault($query)
    {
        return $query->where('group', 'DEFAULT');
    }

    public function scopeMitra($query)
    {
        return $query->where('group', 'MITRA');
    }

    public function scopeContact($query)
    {
        return $query->where('group', 'CONTACT');
    }

    public function scopeSubscribe($query)
    {
        return $query->where('group', 'SUBSCRIBE');
    }
}
