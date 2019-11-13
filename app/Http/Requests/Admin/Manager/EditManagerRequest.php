<?php

namespace App\Http\Requests\Admin\Manager;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditManagerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => [
                'required'
            ],
            'name' => [
                'required',
                Rule::unique('managers', 'name')->ignore($this->input('id'))
            ],
            'description' => [
                'required',
                'max:255'
            ],
        ];
    }
}