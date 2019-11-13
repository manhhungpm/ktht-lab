<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required',
            'password' => [
                'required',
                'max:128',
//                'regex:/^(?=.*[a-z])+(?=.*[A-Z])+(?=.*\d)+(?=.*[$~!#^()@$!%*?&])[A-Za-z\d$~!#^()@$!%*?&]{8,}/',
            ],
        ];
    }
}