<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class IdRequest extends FormRequest
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