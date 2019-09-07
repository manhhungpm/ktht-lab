<?php

namespace App\Http\Requests\Brandname\SmsTypes;

use App\Models\BrandnameSmsType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class EditSmsTypeRequest extends FormRequest
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
//                Rule::unique('brandname_sms_groups', 'name')->ignore($this->input('id'))
                function($attribute, $value, $fail){
                    $smsType = BrandnameSmsType::where('name',$value)->where('group_id',$this->group)->where('id','<>', $this->id)->first();
                    if ($smsType) {
                        return $fail('Đã tồn tại loại tin có nhóm vừa chọn. Vui lòng nhập lại!');
                    }
                },
                'max:225'
            ],
            'description' => ['max:225']
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