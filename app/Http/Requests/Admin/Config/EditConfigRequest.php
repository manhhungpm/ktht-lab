<?php

namespace App\Http\Requests\Admin\Config;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditConfigRequest extends FormRequest{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required',
            'name' => [
                'required',
                Rule::unique('configs', 'name')->ignore($this->input('id'))
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