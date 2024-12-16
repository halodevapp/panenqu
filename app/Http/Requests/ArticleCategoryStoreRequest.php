<?php

namespace App\Http\Requests;

use App\Models\ArticleCategory;
use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class ArticleCategoryStoreRequest extends FormRequest
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $slug = Str::slug($this->category_name);

            $categoryIsExist = ArticleCategory::where('category_slug', $slug)->first();

            if ($categoryIsExist) {
                $validator->errors()->add('category_name', 'Nama kategori sudah ada');
            }
        });
    }
}
