<?php

namespace App\Http\Requests\Brandname\Config\DuplicateConfig;

use Illuminate\Foundation\Http\FormRequest;

class ChangeStatusConfigRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required',
            'status' =>'required'
        ];
    }
}