<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DeviceClass;//分类
use App\Models\DeviceBrand;//品牌
use App\Models\DeviceSupplier;//供应商

class DeviceModel extends Model
{
    protected $table='device_model';
    /**
     * The attributes that are mass assignable.
     *protected $guarded = ['price'];
     * @var array
     */
    protected $fillable = [
        'name', 'brand_id','class_id','hardconfig'
    ];
    public $timestamps = false;

    //一对多反向 一个品牌对应多个型号
    public function brand(){
        return $this->belongsTo(\App\Models\DeviceBrand::class,'brand_id');
    }


    //一对多反向 一个分类对应多个型号
    public function deviceclass(){
        return $this->belongsTo(\App\Models\DeviceClass::class,'class_id');
    }


}
