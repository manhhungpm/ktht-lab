<?php

namespace App\Http\Requests\Device\Store;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditStoreRequest extends FormRequest
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
                Rule::unique('stores', 'name')->ignore($this->input('id'))
            ],
            'description' => [
                'required',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'name' => trans('common.name'),
        ];
    }
}