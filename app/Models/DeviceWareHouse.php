<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceWareHouse extends Model
{
    protected $table='device_warehouses';
    /**
     * The attributes that are mass assignable.
     *protected $guarded = ['price'];
     * @var array
     */
    protected $fillable = [
        'device_model_id',
        'device_supplier_id',
        'device_maintenaceprovier_id',
        'device_financialapproval_id',
        //'barcode',
        'serial_number',
        //'device_ipaddr',
        'device_macaddr',
        'device_price',
        //'device_user',
        'device_registrar',
        //'device_save_addr',
        //'device_trustee',
        //'device_software_config',
        //'equipment_use_id',
        'purchased_date',
        'over_date',
        'start_date',
        'device_work_state',
        'device_save_state',
        //'equipment_archive_id',
        'house_id',
        //'institution_id',
        'comment'
    ];
    //public $timestamps = false;

    //设备对应的 设备 配置 信息 1-1
    public function houseinstitution()
    {   //$this->hasMany($related, $foreignKey, $localKey)

        return $this->hasOne(HouseInstitution::class,'serial_number_id','serial_number');
    }
}
