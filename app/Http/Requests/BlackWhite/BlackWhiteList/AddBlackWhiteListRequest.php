<?php

namespace App\Http\Requests\BlackWhite\BlackWhiteList;

use Illuminate\Foundation\Http\FormRequest;

class AddBlackWhiteListRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'alias' => [
                'required',
                'numeric',
                'unique:alias_black_white_lists'
            ],
            'description' => [
                'max:255'
            ],
            'type' => [
                'required'
            ],
            'provider' => [
                'required'
            ],
            'manager' => [
                'required',
            ]
        ];
    }

    public function attributes()
    {
        return [
            'alias' => trans('blackwhite.alias'),
            'description' => trans('blackwhite.description'),
            'type' => trans('blackwhite.type'),
            'provider' => trans('blackwhite.provider'),
            'manager' => trans('blackwhite.manager'),
        ];
    }
}