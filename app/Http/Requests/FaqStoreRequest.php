<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'question' => [
                'required'
            ],
            'answer' => [
                'required'
            ]
        ];
    }

    public function messages()
    {
        return [
            'question.required' => 'Question wajib diisi',
            'answer.required' => 'Answer wajib diisi',
        ];
    }
}
