<?php

namespace App\Http\Requests\Admin\ManageApi\Api;

use Illuminate\Foundation\Http\FormRequest;

class EditApiRequest extends FormRequest{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required',
            'display_name' => [
                'required'
            ],
        ];
    }
}