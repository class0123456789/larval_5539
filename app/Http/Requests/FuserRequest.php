<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FuserRequest extends FormRequest
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
         //dd($this->route('fuser'));
         //dd($this->route('fuser')->id);
         //$rules['description']='nullable';
         //$rules['description']='nullable';
        // 添加时post方法
        if (request()->isMethod('POST')) {
            $rules['name'] = 'required|unique:users,name|max:255';
            $rules['email'] = 'required|unique:users,email|email|max:255';
            $rules['password']='required|confirmed|min:6|max:50';
            $rules['institution_id']='required';
            $rules['employee_id']='required';
        }else{//修改put方法
            //$this->route('user')->id; //得到路由中role对象的id
           
          
            $rules['name'] = [
                'required',
                'max:255',
                //'unique:users,name',//排除当前ID
                'unique:users,name,'.$this->route('fuser'),//排除当前ID
             //Rule::unique('admin_employees,mobile')->ignore(request()->route('admin/employee'),'id'),
            ];
            $rules['email'] = [
                'required',
                'max:255',
                'unique:users,email,'.$this->route('fuser'),//排除ID
             //Rule::unique('admin_employees,mobile')->ignore(request()->route('admin/employee'),'id'),
            ];
             $rules['password']='nullable|min:6|max:50';//nullable可为空
             $rules['employee_id']='required';
             $rules['institution_id']='required';
            
           // Validator::make($data, [
    //'mobile' => [
    //    'required',
    //    Rule::unique('admin_employees,mobile')->ignore($employee->id),
    //],]);
        }
        return $rules;
    }



}
