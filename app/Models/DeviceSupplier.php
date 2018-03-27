<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceSupplier extends Model
{
    protected $table='device_supplier';
    /**
     * The attributes that are mass assignable.
     *protected $guarded = ['price'];
     * @var array
     */
    protected $fillable = [
        'name', 'contact','phone','address'
    ];
    public $timestamps = false;


}
