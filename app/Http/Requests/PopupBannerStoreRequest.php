<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Models\ProductCategory;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PopupBannerStoreRequest extends FormRequest
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
                'required',
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
            'image.required' => 'Image harus di upload',
            'image.image' => 'Harus file gambar',
            'web_link.required' => 'Web link wajib diisi',
        ];
    }
}
