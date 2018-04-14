<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseInstitution extends Model
{
    protected $table='house_institution';
    /**
     * The attributes that are mass assignable.
     *protected $guarded = ['price'];
     * @var array
     */
    protected $fillable = [
        'serial_number_id',
        'institution_id',
        'ipaddr',
        'networkid',
        'device_save_addr',
        'device_software_config',
        'equipment_use_id',
        'work_date',
        'group_num',
        'layout_num'
    ];
    //public $timestamps = false;
    //对应 设备 硬件信息 ,1对1
    public function  devicewarehouse()
    {
        return $this->belongsTo(DeviceWareHouse::class,'serial_number','serial_number_id');
    }

    //对应 机构 信息 ,1对1
    public function  institution()
    {
        return $this->belongsTo(Institution::class,'id','institution_id');
    }
}
