<?php

namespace App\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditRoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => [
                'required'
            ],
            'name' => [
                'required',
                Rule::unique('roles', 'name')->ignore($this->input('id'))
            ],
            'description' => [
                'required',
                'max:255'
            ],
        ];
    }
}