<?php

namespace App\Http\Requests\Admin\Manager;

use App\Models\AliasBlackWhiteLists;
use Illuminate\Foundation\Http\FormRequest;

class DisableManagerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->request->get('id');
        return [
            'id' => [
                'required',
                function ($attribute, $value, $fail) use ($id) {
                    $item = AliasBlackWhiteLists::where('manager_id',$id)->where('active',ACTIVE)->count();

                    if ($item > 0){
                        $fail("Không thể vô hiệu vì đầu số của đơn vị trong danh sách black/white list đang hoạt động");
                    }
                }
            ],
        ];
    }
}