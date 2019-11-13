<?php

namespace App\Http\Requests\Admin\Manager;

use Illuminate\Foundation\Http\FormRequest;

class AddManagerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'unique:managers',
            ],
            'description' => [
                'required',
                'max:255'
            ],
        ];
    }
}