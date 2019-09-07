<?php

namespace App\Http\Requests\Brandname\Config\TimeWarning;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\BrandnameTimeWarningConfig;

class AddTimeWarningRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $day = array(
                        "day" => $this->request->get('week_day')
                    );
                    $week_day = json_encode($day);

                    $timewarning = BrandnameTimeWarningConfig::where('type_id', $value)
                        ->whereDate('apply_from', '=', $this->request->get('apply_from'))
                        ->where('apply_to', '=', $this->request->get('apply_to'))
                        ->where('week_day', $week_day)
                        ->exists();

                    if ($timewarning) {
                        $fail(trans('brandname.config.validate.duplicate'));
                    }
                }
            ],
            'apply_from' => [
                'required'
            ],
            'apply_to' => [
                'required'
            ],
            'week_day' => [
                'required'
            ],
            'time_frame' => [
                'required',
                function ($attribute, $value, $fail) {
                    //check trung khoang
                    if (sizeof($value) > 1) {
                        $arrTime = $value;
                        for ($i = 0; $i < sizeof($arrTime)-1; $i++) {
                            if ($arrTime[$i + 1]['from'] < $arrTime[$i]['to']) {
                                return $fail(trans('brandname.config.validate.timeframe_error'));
                            }
                        }
                    }
                }
            ],
            'apply_time' => [
                'required'
            ],
        ];
    }

    public function attributes()
    {
        return [
            'type_id' => trans('fields.type_id'),
            'apply_from' => trans('fields.apply_from'),
            'apply_to' => trans('fields.apply_to'),
            'week_day' => trans('fields.week_day'),
            'time_frame' => trans('fields.time_frame'),
        ];
    }
}