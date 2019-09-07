<?php

namespace App\Http\Requests\Brandname\BrandnameList;

use Illuminate\Foundation\Http\FormRequest;


class AddBrandnameRequest extends FormRequest{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'brandname' => [
                'required',
                'unique:ad_brandname',
            ],
            'br_type' => [
                'required'
            ],
            'reason' => [
                'required'
            ],

        ];
    }
}