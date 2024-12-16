<?php

namespace App\Http\Requests;

use App\Models\Store;
use App\Models\StoreType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCreateRequest extends FormRequest
{
    public $storeTypesID = [];
    public $storeTypesName = [];
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        $storeTypes = StoreType::all();
        $this->storeTypesID = $storeTypes->pluck('id')->toArray();
        $this->storeTypesName = implode(",", $storeTypes->pluck('type_name')->toArray());

        return [
            'store_name' => [
                'required',
                'max:100'
            ],
            'store_type_id' => [
                'required',
                Rule::in($this->storeTypesID)
            ],
            'store_phone' => [
                Rule::requiredIf(function () {
                    if ($this->store_type_id == Store::STORE_OFFLINE) {
                        return true;
                    }
                }),
                'nullable',
                'numeric',
                'digits_between:1,15'
            ],
            'store_location' => [
                Rule::requiredIf(function () {
                    if ($this->store_type_id == Store::STORE_OFFLINE) {
                        return true;
                    }
                })
            ],
            'store_link' => [
                Rule::requiredIf(function () {
                    if ($this->store_type_id == Store::STORE_ONLINE) {
                        return true;
                    }
                })
            ],
            'store_image' => [
                Rule::requiredIf(function () {
                    if ($this->store_type_id == Store::STORE_ONLINE) {
                        return true;
                    }
                })
            ]
        ];
    }

    public function messages()
    {
        return [
            'store_name.required' => 'Nama store wajib diisi',
            'store_name.max' => 'Nama store maksimal :max karakter',
            'store_type_id.required' => 'Tipe store wajib dipilih',
            'store_type_id.in' => "Tipe store hanya boleh {$this->storeTypesName}",
            'store_phone.required' => 'Nomor telefon wajib diisi',
            'store_location.required' => 'Lokasi store wajib diisi',
            'store_link.required' => 'Link store wajib diisi',
            'store_image.required' => 'Upload store image',

        ];
    }
}
