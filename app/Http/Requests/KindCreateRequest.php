<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KindCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //$rules['sex'] ="required|boolean";
        // 添加
        if (request()->isMethod('POST')) {
            $rules['name'] = 'required|unique:admin_kinds,name|max:255';
        }else{
            //$this->route('kind')->id; //得到路由中kind对象的id
            // 修改时 request()->method() 方法返回的是 PUT或PATCH
            $rules['name'] = [
                'required','unique:admin_kinds,name,'.$this->route('kind')->id,'max:255'
                //Rule::unique('admin_employees,mobile')->ignore( $id),
            ];
        }
        return $rules;
       
    }
}
