<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MitraFormStoreRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name' => [
                'bail',
                'required',
                'max:100'
            ],
            'phone' => [
                'bail',
                'required',
                'digits_between:1,15'
            ],
            'email' => [
                'bail',
                'required',
                'email',
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
            'name.required' => 'Input nama',
            'name.max' => 'Nama maksimal :max karakter',
            'phone.required' => 'Input No HP',
            'phone.digits_between' => 'Nomor HP maksimal 15 digit',
            'email.required' => 'Input email',
            'email.max' => 'Email maksimal :max karakter',
            'message.required' => 'Input message',
        ];
    }
}
