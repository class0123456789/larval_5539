<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaintenanceProviderRequest extends FormRequest
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
            $rules['name'] = 'required|unique:maintenance_provider,name|max:50';
            //$rules['email'] = 'required|unique:users,email|email|max:255';
            //$rules['password']='required|confirmed|min:6|max:50';
            //$rules['institution_id']='required';
            //$rules['employee_id']='required';
        }else{//修改put方法
            //$this->route('user')->id; //得到路由中role对象的id


            $rules['name'] = [
                'required',
                'max:50',
                //'unique:users,name',//排除当前ID
                'unique:maintenance_provider,name,'.$this->route('maintenanceprovider')->id,//排除当前ID
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
