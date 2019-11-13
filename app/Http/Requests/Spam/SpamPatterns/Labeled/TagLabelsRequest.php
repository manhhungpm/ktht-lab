<?php

namespace App\Http\Requests\Spam\SpamPatterns\Labeled;

use Illuminate\Foundation\Http\FormRequest;

class TagLabelsRequest extends FormRequest{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ids' => 'required',
            'label' => 'required'
        ];
    }
}