<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceBrand extends Model
{
    protected $table='device_brand';
    /**
     * The attributes that are mass assignable.
     *protected $guarded = ['price'];
     * @var array
     */
    protected $fillable = [
        'name', 'contact','phone','address'
    ];
    public $timestamps = false;
    //一对多反向 一个品牌对应多个型号
    public function devicemodels(){
        $this->hasMany(\App\Models\DeviceModel::class,'brand_id');
    }
}
