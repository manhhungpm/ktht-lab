<?php

namespace App\Http\Requests\Admin\ManageApi\ApiAccount;

use Illuminate\Foundation\Http\FormRequest;

class AddApiAccountRequest extends FormRequest
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
                'unique:api_accounts',
            ]
        ];
    }
}