<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class AddUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'unique:users',
            ],
            'display_name' => [
                'required'
            ],
            'email' => [
                'required',
                'max:255',
                'email',
            ],
            'mobile_phone' => [
                'required'
            ]
        ];
    }
}