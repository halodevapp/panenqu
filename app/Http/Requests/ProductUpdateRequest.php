<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProductUpdateRequest extends FormRequest
{
    public $productCategoryID = [];
    public $productCategoryName = [];
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        $productCategory = ProductCategory::all();
        $this->productCategoryID = $productCategory->pluck('id')->toArray();
        $this->productCategoryName = implode(",", $productCategory->pluck('category_name')->toArray());

        return [
            'product_name' => [
                'required',
                'max:100'
            ],
            'product_category' => [
                'required',
                Rule::in($this->productCategoryID)
            ],
            'product_description' => [
                'required'
            ],
            'sequence' => [
                'nullable',
                'integer'
            ]
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => 'Nama produk wajib diisi',
            'product_name.max' => 'Nama produk maksimal :max karakter',
            'product_category.required' => 'Produk kategori wajib dipilih',
            'product_category.in' => "Produk kategori hanya boleh {$this->productCategoryName}",
            'product_description.required' => 'Produk deskripsi wajib diisi',
            'sequence.integer' => 'Sequence harus angka'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $slug = Str::slug($this->product_name);

            $produckIsExist = Product::where('product_slug', $slug)->where('id', '!=', $this->product->id)->first();

            if ($produckIsExist) {
                $validator->errors()->add('product_name', 'Nama produk sudah ada');
            }
        });
    }
}
