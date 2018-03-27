<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceClass extends Model
{
    protected $table='device_class';
    /**
     * The attributes that are mass assignable.
     *protected $guarded = ['price'];
     * @var array
     */
    protected $fillable = [
        'name','parent_id'
    ];
    public $timestamps = false;

    //一对多反向 一个分类对应多个型号
    public function devicemodels(){
        $this->hasMany(\App\Models\DeviceModel::class,'class_id');
    }
}
