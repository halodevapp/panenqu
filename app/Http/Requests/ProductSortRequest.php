<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductSortRequest extends FormRequest
{
    public function rules()
    {
        return [
            'order' => [
                'array',
                'nullable'
            ],
            'order.*' => [
                'required',
                "in:asc,desc,ASC,DESC"
            ],
        ];
    }

    public function messages()
    {
        return [
            "order.*.required" => "Order by product harus diisi",
            "order.*.in" => "Order by product ASC/DESC",
        ];
    }
}
