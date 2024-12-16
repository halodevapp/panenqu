<?php

namespace App\Http\Requests;

use App\Models\ContactFormCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactFormStoreRequest extends FormRequest
{
    public $formCategoryID = [];
    public $formCategoryName = [];

    public function rules()
    {

        $formCategory = ContactFormCategory::all();
        $this->formCategoryID = $formCategory->pluck('id')->toArray();
        $this->formCategoryName = implode(",", $formCategory->pluck('category_name')->toArray());

        return [
            'contact_category' => [
                'bail',
                'required',
                Rule::in($this->formCategoryID)
            ],
            'name' => [
                'bail',
                'required',
                'max:100'
            ],
            'email' => [
                'bail',
                'required',
                'email',
                'max:100'
            ],
            'subject' => [
                'bail',
                'required',
                'max:100'
            ],
            'message' => [
                'bail',
                'required'
            ]
        ];
    }

    public function messages()
    {
        return [
            'contact_category.required' => 'Pilih kategori',
            'contact_category.in' => "Kategori hanya boleh {$this->formCategoryName}",
            'name.required' => 'Input nama',
            'name.max' => 'Nama maksimal :max karakter',
            'email.required' => 'Input email',
            'email.max' => 'Email maksimal :max karakter',
            'subject.required' => 'Input subject',
            'subject.max' => 'Subject maksimal :max karakter'
        ];
    }
}
