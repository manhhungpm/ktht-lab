<?php

namespace App\Http\Requests\Brandname\Config\DuplicateConfig;

use App\Models\BrandnameSmsDuplicateConfig;
use Illuminate\Foundation\Http\FormRequest;

class EditDuplicateConfigRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->request->get('id');
        $type_id = $this->request->get('type_id');
        $apply_from = $this->request->get('apply_from');
        $apply_to = $this->request->get('apply_to');
        $high_warning_to = $this->request->get('high_warning_to');
        $danger_warning_to = $this->request->get('danger_warning_to');
        $crisis_warning_to = $this->request->get('crisis_warning_to');

        return [
            'id' => [
                'required'
            ],
            'high_warning_from' => [
                'required',
                'numeric',
                'max:999999999999',
                function ($attribute, $value, $fail) use ($high_warning_to) {
                    if ($value > $high_warning_to) {
                        return $fail(trans('brandname.config.duplicate_config.to_value_greater_from_value'));
                    }
                }
            ],
            'high_warning_to' => [
                'required',
                'numeric',
                'max:999999999999'
            ],
            'danger_warning_from' => [
                'required',
                'numeric',
                'max:999999999999',
                function ($attribute, $value, $fail) use ($danger_warning_to, $high_warning_to) {
                    if ($value > $danger_warning_to) {
                        return $fail(trans('brandname.config.duplicate_config.to_value_greater_from_value'));
                    }
                    if ($value <= $high_warning_to) {
                        return $fail(trans('brandname.config.segment_warning_config.danger_warning_from_is_greater_high_warning_to_per_day'));
                    }
                }
            ],
            'danger_warning_to' => [
                'required',
                'numeric',
                'max:999999999999'
            ],
            'crisis_warning_from' => [
                'required',
                'numeric',
                'max:999999999999',
                function ($attribute, $value, $fail) use ($crisis_warning_to, $danger_warning_to) {
                    if ($value > $crisis_warning_to) {
                        return $fail(trans('brandname.config.duplicate_config.to_value_greater_from_value'));
                    }
                    if ($value <= $danger_warning_to) {
                        return $fail(trans('brandname.config.segment_warning_config.crisis_warning_from_is_greater_danger_warning_to_per_day'));
                    }
                }
            ],
            'crisis_warning_to' => [
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
                function ($attribute, $value, $fail) use ($type_id, $apply_from, $apply_to, $id) {
                    if (BrandnameSmsDuplicateConfig::where('id', '<>', $id)->where('type_id', $type_id)->whereDate('apply_from', '>=', $apply_from)->whereDate('apply_to', '<=', $apply_to)->count() > 0) {
                        $fail(trans('brandname.config.duplicate_config.unique'));
                    }
                    if (BrandnameSmsDuplicateConfig::where('id', '<>', $id)->where('type_id', $type_id)->whereDate('apply_from', '<=', $apply_from)->whereDate('apply_to', '>=', $apply_from)->count() > 0) {
                        $fail(trans('brandname.config.duplicate_config.unique'));
                    }
                    if (BrandnameSmsDuplicateConfig::where('id', '<>', $id)->where('type_id', $type_id)->whereDate('apply_from', '<=', $apply_to)->whereDate('apply_to', '>=', $apply_to)->count() > 0) {
                        $fail(trans('brandname.config.duplicate_config.unique'));
                    }
                }
            ]
        ];
    }
}