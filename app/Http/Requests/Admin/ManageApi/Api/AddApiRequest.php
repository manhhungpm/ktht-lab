<?php

namespace App\Http\Requests\Admin\ManageApi\Api;

use Illuminate\Foundation\Http\FormRequest;

class AddApiRequest extends FormRequest{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'unique:apis',
            ],
            'display_name' => [
                'required'
            ],
        ];
    }
}