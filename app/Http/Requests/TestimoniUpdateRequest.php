<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestimoniUpdateRequest extends FormRequest
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
                'required',
                'max:100'
            ],
            'profession' => [
                'required',
                'max:100'
            ],
            'rating' => [
                'required',
                'numeric',
                'min:1',
                'max:5'
            ],
            'testimoni' => [
                'required'
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama wajib diisi',
            'name.max' => 'Nama maksimal :max karakter',
            'profession.required' => 'Pekerjaan wajib diisi',
            'profession.max' => 'Pekerjaan maksimal :max karakter',
            'rating.required' => 'Rating wajib diisi',
            'rating.min' => 'Rating minimal :min',
            'rating.max' => 'Rating maksimal :max',
            'rating.numeric' => 'Rating harus number',
            'testimoni.required' => 'Isi testimoni',
        ];
    }
}
