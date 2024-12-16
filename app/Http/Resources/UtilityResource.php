<?php

namespace App\Http\Resources;

use App\Helpers\MediaStorage;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UtilityResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'WA_NUMBER' => $this['WA_NUMBER'],
            'WA_TEMPLATE' => $this['WA_TEMPLATE'],
            'COMPANY_ADDRESS' => $this['COMPANY_ADDRESS'],
            'COMPANY_NAME' => $this['COMPANY_NAME'],
            'COMPANY_EMAIL' => $this['COMPANY_EMAIL'],
            'NOTIF' => $this['NOTIF'],
        ];
    }
}
