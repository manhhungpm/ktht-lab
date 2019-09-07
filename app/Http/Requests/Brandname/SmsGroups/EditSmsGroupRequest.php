<?php

namespace App\Http\Requests\Brandname\SmsGroups;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class EditSmsGroupRequest extends FormRequest
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
                Rule::unique('brandname_sms_groups', 'name')->ignore($this->input('id')),
                'max:100'
            ],
            'description' => ['max:255']
        ];
    }

    public function attributes()
    {
        return [
            'name' => trans('fields.name'),
            'description' => trans('fields.description')
        ];
    }
}