<?php

namespace App\Http\Requests\Admin\Faculty;

use Illuminate\Foundation\Http\FormRequest;

class AddFacultyRequest extends FormRequest
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
                'unique:faculty',
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
