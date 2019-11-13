<?php

namespace App\Http\Requests\Spam\SpamPatterns\Labeled;

use Illuminate\Foundation\Http\FormRequest;

class AddLabelRequest extends FormRequest{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'label' => 'required',
            'content'=>'required|min:10'
        ];
    }
}