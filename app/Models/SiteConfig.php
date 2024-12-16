<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteConfig extends Model
{
    use HasFactory, SoftDeletes;

    const WHATSAPP_GROUP = 'WA';
    const WHATSAPP_NUMBER = 'WA_NUMBER';
    const WHATSAPP_TEMPLATE = 'WA_TEMPLATE';

    const NOTIF_GROUP = 'NOTIF';
    const NOTIF_TYPE = 'NOTIF';

    const COMPANY_GROUP = 'COMPANY';
    const COMPANY_ADDRESS = 'COMPANY_ADDRESS';
    const COMPANY_EMAIL = 'COMPANY_EMAIL';

    protected $guarded = ['id'];

    public function scopeNotifications($query)
    {
        return $query->where('group', self::NOTIF_GROUP);
    }

    public function scopeWhatsapp($query)
    {
        return $query->where('group', self::WHATSAPP_GROUP);
    }

    public function scopeCompany($query)
    {
        return $query->where('group', self::COMPANY_GROUP);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault([
            'name' => ''
        ]);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault([
            'name' => ''
        ]);
    }
}
