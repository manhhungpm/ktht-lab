<?php

namespace App\Http\Requests\Brandname\Config;

use Illuminate\Foundation\Http\FormRequest;


class AddConfigRequest extends FormRequest{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date_threshold' => [
                'required',
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