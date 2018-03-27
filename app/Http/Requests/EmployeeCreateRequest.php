<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Validator;

class EmployeeCreateRequest extends FormRequest
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
       //dd($this->route('employee')->id);
        $rules['name'] = 'required|max:255';
        $rules['post'] = 'nullable|string';
        $rules['sex'] ="required|boolean";
        $rules['institution_id'] ="required";
        // 添加
        if (request()->isMethod('POST')) {
            $rules['mobile'] = 'required|unique:admin_employees,mobile|digits_between:11,11';
        }else{
            //$this->route('employee')->id; //得到路由中employee对象的id
           
          
            $rules['mobile'] = [
                'required',
                'digits_between:11,11',
                'unique:admin_employees,mobile,'.$this->route('employee')->id,//排除ID
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
