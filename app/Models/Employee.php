<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Institution
 *  员工表
 * @mixin \Eloquent
 */
class Employee extends Model
{
     protected $table='admin_employees';
     //protected $guarded = [];
     protected $fillable = [
        'name', 'post', 'sex','mobile','institution_id'
    ];
    // public function institutions(){
    //     return $this->belongsToMany(Institution::class,'admin_employee_institution','employee_id','institution_id')->withPivot(['employee_id','institution_id']);
    // }
    //取机构信息
    public function institution(){
        return $this->belongsTo(Institution::class);
    }
     //取前台用户
     public function user() {
         return $this->hasOne(User::class);
     }
}
