<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeviceClassRequest extends FormRequest
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
            $rules['name'] = 'required|unique:device_class,name|max:255';
            $rules['parent_id']='required|integer';
        }else{//修改put方法
            //$this->route('user')->id; //得到路由中role对象的id


            $rules['name'] = [
                'required',
                'max:255',
                //'unique:users,name',//排除当前ID
                'unique:device_class,name,'.$this->route('deviceclass')->id,//排除当前ID
                //Rule::unique('admin_employees,mobile')->ignore(request()->route('admin/employee'),'id'),
            ];

            $rules['parent_id']='required|integer';

            // Validator::make($data, [
            //'mobile' => [
            //    'required',
            //    Rule::unique('admin_employees,mobile')->ignore($employee->id),
            //],]);
        }
        return $rules;
    }
}
