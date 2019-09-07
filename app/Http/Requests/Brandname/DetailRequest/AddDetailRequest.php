<?php

namespace App\Http\Requests\Brandname\DetailRequest;

use Illuminate\Foundation\Http\FormRequest;


class AddDetailRequest extends FormRequest{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phone' => [
                'required',
                'max:100'
            ],
        ];
    }

    public function attributes()
    {
        return [
            'phone' => trans('fields.phone'),
        ];
    }
}