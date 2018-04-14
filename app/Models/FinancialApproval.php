<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialApproval extends Model
{
    protected $table='device_financial_approval';
    /**
     * The attributes that are mass assignable.
     *protected $guarded = ['price'];
     * @var array
     */
    protected $fillable = [
        'file_no', 'file_url','house_id'
    ];
    //public $timestamps = false;
}
