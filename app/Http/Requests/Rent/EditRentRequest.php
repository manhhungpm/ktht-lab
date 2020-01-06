<?php

namespace App\Http\Requests\Rent;

use App\Models\DeviceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditRentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'id' => [
                'required'
            ],
            'date_range' => [
                'required',
            ],
            'user' => [
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
                            return $fail('Không đủ thiết bị trong kho');
                        } else {
                            $amountDeviceRent = $value1['pivot']['amount']; //Đang mượn bao nhiêu
                            if ($amountDeviceRent < $value1['amount']) {     //Nếu mượn thêm thiết bị

                                //Tính chênh lệch
                                $offset= $value1['amount'] - $amountDeviceRent ;

                                $newAmount = $amountDevice[0]['amount'] - $offset;
                                $query = DeviceType::select('id', 'amount')
                                    ->where('id', $value1['device_type']['id'])
                                    ->update(['amount' => $newAmount]);
                            } else {   //Nếu trả bớt thiết bị đi

                                //Tính chênh lệch
                                $offset= $amountDeviceRent - $value1['amount'] ;

                                $newAmount = $amountDevice[0]['amount'] + $offset;
                                $query = DeviceType::select('id', 'amount')
                                    ->where('id', $value1['device_type']['id'])
                                    ->update(['amount' => $newAmount]);
                            }
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
