<?php

namespace App\Http\Requests\Brandname\BrandnameList;

use Illuminate\Foundation\Http\FormRequest;

class ActiveBrandnameRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required',
        ];
    }
}