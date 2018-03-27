<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstitutionRequest extends FormRequest
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
        $rules['parent_id'] = "sometimes|required|integer";
        $rules['kind_id'] = "sometimes|required|integer";
        //$rules['sex'] ="required|boolean";
        // 添加
        if (request()->isMethod('POST')) {
            $rules['name'] = 'required|unique:admin_institutions,name|max:255';
        }else{
            //$this->route('institution')->id; //得到路由中institution对象的id
            // 修改时 request()->method() 方法返回的是 PUT或PATCH
            $rules['name'] = [
                'required','unique:admin_institutions,name,'.$this->route('institution')->id,'max:255'
                //Rule::unique('admin_employees,mobile')->ignore( $id),
            ];
        }
        return $rules;

        
        //$rules['name'] = 'required';
        // 添加时
        //if (request()->isMethod('POST')) {
        //    $rules['slug'] = 'required|unique:roles,slug';
        //}else{
            // 修改时 request()->method() 方法返回的是 PUT或PATCH
        //    $rules['slug'] = [
        //        'required',

        //    ];
        //}
        //return $rules;
    }



}
