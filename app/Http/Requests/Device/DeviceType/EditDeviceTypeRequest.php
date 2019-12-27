<?php

namespace App\Http\Requests\Device\DeviceType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditDeviceTypeRequest extends FormRequest
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
                Rule::unique('devices', 'name')->ignore($this->input('id'))
            ],
            'display_name'=> [
                'required',
            ],
            'amount' => [
                'required',
            ],
            'store_id' => [
                'required',
            ],
            'device_group_id' => [
                'required',
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

