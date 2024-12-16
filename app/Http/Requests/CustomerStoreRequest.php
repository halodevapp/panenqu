<?php

namespace App\Http\Requests;

use App\Models\Customer;
use App\Models\CustomerCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerStoreRequest extends FormRequest
{
    public $categoryID = [];
    public $categoryName = [];
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        $categories = CustomerCategory::all();
        $this->categoryID = $categories->pluck('id')->toArray();
        $this->categoryName = implode(",", $categories->pluck('type_name')->toArray());

        return [
            'customer_name' => [
                'required',
                'max:100'
            ],
            'customer_category' => [
                'required',
                Rule::in($this->categoryID)
            ],
            'customer_image' => [
                'required'
            ]
        ];
    }

    public function messages()
    {
        return [
            'customer_name.required' => 'Nama customer wajib diisi',
            'customer_name.max' => 'Nama customer maksimal :max karakter',
            'customer_category.required' => 'Category customer wajib dipilih',
            'customer_category.in' => "Category customer hanya boleh {$this->categoryName}",
            'customer_image.required' => 'Upload customer image',

        ];
    }
}
