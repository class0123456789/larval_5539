<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionCreateRequest extends FormRequest
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

        
         $rules['description']='nullable';
        // 添加时post方法
        if (request()->isMethod('POST')) {
            $rules['name'] = 'required|unique:admin_permissions,name|max:255';
            $rules['slug'] = 'required|unique:admin_permissions,slug|max:255';
        }else{//修改put方法
            //$this->route('permission')->id; //得到路由中role对象的id
           
          
            $rules['name'] = [
                'required',
                'max:255',
                'unique:admin_permissions,name,'.$this->route('permission')->id,//排除ID
             //Rule::unique('admin_employees,mobile')->ignore(request()->route('admin/employee'),'id'),
            ];
            $rules['slug'] = [
                'required',
                'max:255',
                'unique:admin_permissions,slug,'.$this->route('permission')->id,//排除ID
             //Rule::unique('admin_employees,mobile')->ignore(request()->route('admin/employee'),'id'),
            ];
            
           // Validator::make($data, [
    //'mobile' => [
    //    'required',
    //    Rule::unique('admin_employees,mobile')->ignore($employee->id),
    //],]);
        }
        return $rules;
    }
}
