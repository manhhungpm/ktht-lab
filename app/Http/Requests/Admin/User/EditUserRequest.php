<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required',
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