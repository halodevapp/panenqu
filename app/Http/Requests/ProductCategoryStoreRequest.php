<?php

namespace App\Http\Requests;

use App\Models\ProductCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ProductCategoryStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'category_name' => [
                'required',
                'max:100'
            ],
            'category_image' => [
                'required'
            ]
        ];
    }

    public function messages()
    {
        return [
            'category_name.required' => 'Nama kategori wajib diisi',
            'category_name.max' => 'Nama kategori maksimak :max karakter',
            'category_image.required' => 'Upload category image',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $slug = Str::slug($this->category_name);

            $categoryIsExist = ProductCategory::where('category_slug', $slug)->first();

            if ($categoryIsExist) {
                $validator->errors()->add('category_name', 'Nama kategori sudah ada');
            }
        });
    }
}
