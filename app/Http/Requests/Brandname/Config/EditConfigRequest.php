<?php

namespace App\Http\Requests\Brandname\Config;

use Illuminate\Foundation\Http\FormRequest;


class EditConfigRequest extends FormRequest{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required',
            'date_threshold' => [
                'required'
            ],
            'month_threshold' => [
                'required'
            ],
            'br_type' => [
                'required'
            ]
        ];
    }
}