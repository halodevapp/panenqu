<?php

namespace App\Http\Requests;

use App\Models\ArticleCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CustomerCategoryUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'category_name' => [
                'required',
                'max:100'
            ],
            'sequence' => [
                'required',
                'integer'
            ]
        ];
    }

    public function messages()
    {
        return [
            'category_name.required' => 'Nama kategori wajib diisi',
            'category_name.max' => 'Nama kategori maksimak :max karakter',
            'sequence.required' => 'Sequence wajib diisi',
            'sequence.integer' => 'Sequence wajib angka',
        ];
    }
}
