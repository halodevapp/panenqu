<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriberStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => [
                'bail',
                'required',
                'email',
                'max:100'
            ]
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Input email',
            'email.max' => 'Email maksimal :max karakter',
        ];
    }
}
