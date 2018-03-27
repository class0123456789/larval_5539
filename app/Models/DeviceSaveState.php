<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceSaveState extends Model
{
    protected $table='device_save_state';
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
