<?php

namespace App\Http\Requests\Admin\ManageApi\ApiAccount;

use Illuminate\Foundation\Http\FormRequest;

class ActiveApiAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required',
        ];
    }
}
