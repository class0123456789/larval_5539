<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserCreateRequest extends FormRequest
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

       //dd($this->route('user')->id);
        
         //$rules['description']='nullable';
         //$rules['description']='nullable';
        // 添加时post方法
        if (request()->isMethod('POST')) {
            $rules['name'] = 'required|unique:admin_users,name|max:255';
            $rules['email'] = 'required|unique:admin_users,email|email|max:255';
            $rules['password']='required|confirmed|min:6|max:50';
        }else{//修改put方法
            //$this->route('user')->id; //得到路由中role对象的id
           
          
            $rules['name'] = [
                'required',
                'max:255',
                'unique:admin_users,name,'.$this->route('user')->id,//排除当前ID
             //Rule::unique('admin_employees,mobile')->ignore(request()->route('admin/employee'),'id'),
            ];
            $rules['email'] = [
                'required',
                'max:255',
                'unique:admin_users,email,'.$this->route('user')->id,//排除ID
             //Rule::unique('admin_employees,mobile')->ignore(request()->route('admin/employee'),'id'),
            ];
             $rules['password']='nullable|min:6|max:50';
            
           // Validator::make($data, [
    //'mobile' => [
    //    'required',
    //    Rule::unique('admin_employees,mobile')->ignore($employee->id),
    //],]);
        }
        return $rules;
    }
}
