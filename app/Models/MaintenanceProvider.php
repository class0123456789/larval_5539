<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceProvider extends Model
{
    protected $table='maintenance_provider';
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
