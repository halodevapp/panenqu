<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RoleStoreRequest extends FormRequest
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
                Rule::unique(Role::class, 'name')
            ]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama role wajib diisi',
            'name.unique' => 'Role :input sudah ada'
        ];
    }
}
