<?php

namespace App\Http\Requests\Brandname\Config;

use Illuminate\Foundation\Http\FormRequest;


class DisableConfigRequest extends FormRequest{
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
        ];
    }
}