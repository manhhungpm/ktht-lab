<?php

namespace App\Http\Requests\Brandname\SmsGroups;

use Illuminate\Foundation\Http\FormRequest;


class AddSmsGroupRequest extends FormRequest{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'unique:brandname_sms_groups',
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