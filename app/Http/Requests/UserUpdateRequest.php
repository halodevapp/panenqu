<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'name' => [
                'required'
            ],
            'password' => [
                'nullable',
                'min:8'
            ]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama user wajib diisi',
            'password.min' => 'Password minimal :min karakter',
        ];
    }
}
