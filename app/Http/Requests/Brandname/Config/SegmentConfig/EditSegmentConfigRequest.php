<?php

namespace App\Http\Requests\Brandname\Config\SegmentConfig;

use App\Models\BrandnameSmsSegmentConfig;
use Illuminate\Foundation\Http\FormRequest;

class EditSegmentConfigRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->request->get('id');
        $name = $this->request->get('name');
        $type_id = $this->request->get('type_id');
        $apply_from = $this->request->get('apply_from');
        $apply_to = $this->request->get('apply_to');

        return [
            'id'=>[
              'required'
            ],
            'name' => [
                'required',
            ],
            'per_day' => [
                'required',
                'numeric',
                'max:999999999999'
            ],
            'per_month' => [
                'required',
                'numeric',
                'max:999999999999'
            ],
            'type_id' => [
                'required'
            ],
            'apply_from' => [
                'required'
            ],
            'apply_to' => [
                'required'
            ],
            'apply_time' => [
                function ($attribute, $value, $fail) use($name,$type_id,$apply_from,$apply_to,$id) {
                    if(BrandnameSmsSegmentConfig::where('name',$name)->where('id','<>',$id)->where('type_id',$type_id)->whereDate('apply_from','>=',$apply_from)->whereDate('apply_to','<=',$apply_to)->count()>0){
                        $fail(trans('brandname.config.segment_config.unique'));
                    }
                    if(BrandnameSmsSegmentConfig::where('name',$name)->where('id','<>',$id)->where('type_id',$type_id)->whereDate('apply_from','<=',$apply_from)->whereDate('apply_to','>=',$apply_from)->count()>0){
                        $fail(trans('brandname.config.segment_config.unique'));
                    }
                    if(BrandnameSmsSegmentConfig::where('name',$name)->where('id','<>',$id)->where('type_id',$type_id)->whereDate('apply_from','<=',$apply_to)->whereDate('apply_to','>=',$apply_to)->count()>0){
                        $fail(trans('brandname.config.segment_config.unique'));
                    }
                }
            ]
        ];
    }
}