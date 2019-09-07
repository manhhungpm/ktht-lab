<?php

namespace App\Http\Requests\Brandname\BrandnameList;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class EditBrandnameRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required',
            'brandname' => [
                'required',
                Rule::unique('ad_brandname', 'brandname')->ignore($this->input('id'))
            ],
            'br_type' => 'required',
            'reason' => 'required'
        ];
    }
}