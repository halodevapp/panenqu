<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormCategoryStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'category_name' => [
                'required',
                'max:100'
            ]
        ];
    }

    public function messages()
    {
        return [
            'category_name.required' => 'Nama kategori wajib diisi',
            'category_name.max' => 'Nama kategori maksimak :max karakter'
        ];
    }
}
