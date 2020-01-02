<?php

namespace App\Http\Requests\Device\DeviceGroup;

use Illuminate\Foundation\Http\FormRequest;

class AddDeviceGroupRequest extends FormRequest
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
                'unique:group_devices',
            ],
            'display_name' => [
                'required'
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

