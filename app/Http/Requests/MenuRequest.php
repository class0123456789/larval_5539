<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
        $rule =  [
            'name' => 'required|max:255',
            'pid' => 'required',
            'slug' => 'required',
        ];
        if ($this->pid) {//增加菜单必须要写url，[顶级菜单可以不填写次项]
            $rule['url'] = 'required';
        }
        return $rule;
    }
}
