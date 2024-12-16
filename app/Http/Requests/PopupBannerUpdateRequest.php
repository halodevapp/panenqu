<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class PopupBannerUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'description' => [
                'required',
            ],
            'start_date' => [
                'required',
                'date_format:Y-m-d',
                function ($attribute, $value, $fails) {
                    $startDate = Carbon::createFromFormat('Y-m-d', $value);
                    $endDate = Carbon::createFromFormat('Y-m-d', $this->end_date);

                    if ($startDate->greaterThan($endDate)) {
                        $fails("Start date tidak boleh lebih dari end date");
                        return;
                    }
                }
            ],
            'end_date' => [
                'required',
                'date_format:Y-m-d'
            ],
            'web_link' => [
                'required',
            ],
            'image' => [
                'nullable',
                'image'
            ]
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'Description wajib diisi',
            'start_date.required' => 'Start date wajib diisi',
            'start_date.date_format' => 'Format start date harus tahun-bulan-hari',
            'end_date.required' => 'End date wajib diisi',
            'end_date.date_format' => 'Format end date harus tahun-bulan-hari',
            'image.image' => 'Harus file gambar',
            'web_link.required' => 'Web link wajib diisi',
        ];
    }
}
