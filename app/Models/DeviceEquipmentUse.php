<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceEquipmentUse extends Model
{
    protected $table='device_equipment_use';
    /**
     * The attributes that are mass assignable.
     *protected $guarded = ['price'];
     * @var array
     */
    protected $fillable = [
        'name'
    ];
    public $timestamps = false;
}
