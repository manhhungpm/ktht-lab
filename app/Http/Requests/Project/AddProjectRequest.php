<?php

namespace App\Http\Requests\Project;

use App\Models\DeviceType;
use Illuminate\Foundation\Http\FormRequest;

class AddProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $amount = $this->request->get('amount');
//        dd($multiDevice);
        return [
            'name' => [
                'required',
                'unique:projects',
            ],
            'user_id' => [
                'required',
            ],
            'device_type_id' => [
                'required',
            ],
            'description' => [
                'required',
            ],
            'multi_device_details'=>[
                function ($attribute, $value, $fail) use ($amount) {
                    foreach ($value as $key => $item){
                        $amountDevice = DeviceType::select('id','amount')->where('id',$item['device_type']['id'])->get()->toArray();
//                        dd($amountDevice[0]['amount']);
                        if($amount > $amountDevice[0]['amount']){
                            return $fail('Không đủ thiết bị trong kho');
                        }
                    }
                }
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
