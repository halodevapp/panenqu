<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class PermissionStoreRequest extends FormRequest
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
                Rule::unique(Permission::class, 'name')
            ],
            'description' => [
                'required'
            ]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama permission wajib diisi',
            'name.unique' => 'Permission :input sudah ada',
            'description.required' => 'Description wajib diisi'
        ];
    }
}
