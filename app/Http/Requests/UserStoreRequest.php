<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'email' => [
                'required',
                'email',
                'unique:App\Models\User,email'
            ],
            'name' => [
                'required'
            ],

            'password' => [
                'required',
                'min:8'
            ],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email user wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email :input sudah terdaftar',
            'name.required' => 'Nama user wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal :min karakter',
        ];
    }
}
