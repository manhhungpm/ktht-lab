<?php

namespace App\Http\Requests\Admin\Config;

use Illuminate\Foundation\Http\FormRequest;

class AddConfigRequest extends FormRequest
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
                'unique:configs',
            ],
            'group_name'  => [
                'required'
            ],
            'value' => [
                'required'
            ]
        ];
    }
}