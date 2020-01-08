<?php

namespace App\Http\Requests\Project;

use App\Models\DeviceRent;
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
        $rules = [
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
        ];

        $details = $this->request->get('multi_device_details');
        foreach ($details as $index => $value1) {
//          value1['amount'] là sl nhập vào
            $rules['multi_device_details.' . $index] = [
                function ($attribute, $value, $fail) use ($index, $details, $value1) {
                    for ($i = 0; $i < count($details); $i++) {
                        $amountDevice = DeviceType::select('id', 'amount')->where('id', $value1['device_type']['id'])->get()->toArray();
                        if ($value1['amount'] > $amountDevice[0]['amount']) {
                            return $fail('Không đủ thiết bị trong kho. Thiết bị chỉ còn: '.$amountDevice[0]['amount']);
                        } else {
                            $newAmount = $amountDevice[0]['amount'] - $value1['amount']; //Trừ đi cái mượn
                            $query = DeviceType::select('id', 'amount')
                                ->where('id', $value1['device_type']['id'])
                                ->update(['amount' => $newAmount]);
                        }
                    }
                }
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => trans('common.name'),
        ];
    }
}
