<?php

namespace App\Http\Requests\Device\DeviceType;

use Illuminate\Foundation\Http\FormRequest;

class AddDeviceTypeRequest extends FormRequest
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
                'unique:devices',
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
