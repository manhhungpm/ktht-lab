<?php

namespace App\Http\Requests\Device\DeviceGroup;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditDeviceGroupRequest extends FormRequest
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
                Rule::unique('group_devices', 'name')->ignore($this->input('id'))
            ],
            'description' => [
                'required',
            ],
            'provider_id' => [
                'required'
            ]
        ];
    }

    public function attributes()
    {
        return [
            'name' => trans('common.name'),
        ];
    }
}
