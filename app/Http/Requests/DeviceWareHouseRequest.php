<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeviceWareHouseRequest extends FormRequest
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
        $rules['device_model_id'] = "sometimes|required|integer";
        $rules['device_supplier_id'] = "sometimes|required|integer";
        $rules['device_maintenaceprovier_id'] = "sometimes|required|integer";
        $rules['device_financialapproval_id'] = "sometimes|nullable";


        //$rules['device_ipaddr'] = "sometimes|nullable|max:15";
        $rules['device_macaddr'] = "sometimes|nullable|max:18";
        $rules['device_price'] = "sometimes|required|double";

        //$rules['device_user'] = "sometimes|nullable|max:10";
        $rules['device_registrar'] = "sometimes|required|max:10";
        //$rules['device_save_addr'] = "sometimes|nullable|max:10";
        //$rules['device_software_config'] = "sometimes|required|date";





        $rules['purchased_date'] = "sometimes|required|date";
        $rules['over_date'] = "sometimes|required|date";
        $rules['start_date'] = "sometimes|required|date";

        $rules['device_work_state'] = "sometimes|required|integer";
        $rules['device_save_state'] = "sometimes|required|integer";
        //$rules['equipment_archive_id'] = "sometimes|required|integer";
        //$rules['institution_id'] = "sometimes|required|integer";
        $rules['house_id'] = "sometimes|required|integer";
        $rules['comment'] = "sometimes|nullable";
        //$rules['sex'] ="required|boolean";
        // 添加
        if (request()->isMethod('POST')) {
            //$rules['device_model_id'] = 'required|unique:device_model,name|max:255';
            //$rules['barcode'] = "sometimes|required|unique:device_warehouses,barcode|max:20";
            $rules['serial_number'] = "sometimes|required|unique:device_warehouses,serial_number|max:20";
        }else{
            //$this->route('institution')->id; //得到路由中institution对象的id
            // 修改时 request()->method() 方法返回的是 PUT或PATCH
            //$rules['barcode'] = [
            //    'required','unique:device_warehouses,barcode,'.$this->route('devicewarehouse')->id,'max:20'
            //    //Rule::unique('admin_employees,mobile')->ignore( $id),
            //];
            $rules['serial_number'] = [
                'required','unique:device_warehouses,serial_number,'.$this->route('devicewarehouse')->id,'max:20'
                //Rule::unique('admin_employees,mobile')->ignore( $id),
            ];
        }
        return $rules;
    }
}
