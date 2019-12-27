<?php

namespace App\Http\Requests\Device\Provider;

use Illuminate\Foundation\Http\FormRequest;

class AddProviderRequest extends FormRequest
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
                'unique:providers',
            ],
            'address' => [
                'required'
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
