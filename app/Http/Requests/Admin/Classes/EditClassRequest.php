<?php
namespace App\Http\Requests\Admin\Classes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditClassRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => [
                'required'
            ],
            'name' => [
                'required',
                Rule::unique('classes', 'name')->ignore($this->input('id'))
            ],
            'description' => [
                'required',
            ],
            'faculty_id' =>[
                'required'
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
