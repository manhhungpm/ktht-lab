<?php

namespace App\Http\Requests\BlackWhite\BlackWhiteList;

use Illuminate\Foundation\Http\FormRequest;

class EditBlackWhiteListRequest extends FormRequest
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
            'alias' => [
                'required',
                'max:15',
                'numeric',
                Rule::unique('alias_black_white_lists', 'alias')->ignore($this->input('id'))
            ],
            'description' => [
                'max:255'
            ],
            'type' => [
                'required'
            ],
            'provider' => [
                'required'
            ],
            'manager' => [
                'required',
            ]
        ];
    }
}