<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class DisableUserRequest extends FormRequest{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required',
        ];
    }
}