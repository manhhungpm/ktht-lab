<?php

namespace App\Http\Requests\Brandname\Config;

use Illuminate\Foundation\Http\FormRequest;


class ActiveConfigRequest extends FormRequest{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => [
                'required',
            ],
            'br_type' => [
                'required'
            ]
        ];
    }
}