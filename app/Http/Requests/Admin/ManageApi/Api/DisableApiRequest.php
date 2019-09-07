<?php

namespace App\Http\Requests\Admin\ManageApi\Api;

use Illuminate\Foundation\Http\FormRequest;

class DisableApiRequest extends FormRequest{
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