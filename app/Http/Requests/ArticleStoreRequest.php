<?php

namespace App\Http\Requests;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ArticleStoreRequest extends FormRequest
{
    public $articleCategoryID = [];
    public $articleCategoryName = [];
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        $articleCategory = ArticleCategory::all();
        $this->articleCategoryID = $articleCategory->pluck('id')->toArray();
        $this->articleCategoryName = implode(",", $articleCategory->pluck('category_name')->toArray());

        return [
            'article_title' => [
                'required',
                'max:100'
            ],
            'article_category' => [
                'required',
                Rule::in($this->articleCategoryID)
            ],
            'article_content' => [
                'required'
            ],
            'article_image_thumbnail' => [
                'required'
            ],
            'article_image_banner' => [
                'required'
            ]
        ];
    }

    public function messages()
    {
        return [
            'article_title.required' => 'Article title wajib diisi',
            'article_title.max' => 'Article title maksimal :max karakter',
            'article_category.required' => 'Article kategori wajib dipilih',
            'article_category.in' => "Article kategori hanya boleh {$this->articleCategoryName}",
            'article_content.required' => 'Article konten wajib diisi',
            'article_image_thumbnail.required' => 'Article thumbnail image wajib diupload',
            'article_image_banner.required' => 'Article banner image wajib diupload',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $slug = Str::slug($this->product_title);

            $articleIsExist = Article::where('article_slug', $slug)->first();

            if ($articleIsExist) {
                $validator->errors()->add('article_title', 'Article title sudah ada');
            }
        });
    }
}
