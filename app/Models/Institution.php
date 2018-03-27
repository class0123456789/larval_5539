<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Institution
 *  机构表
 * @mixin \Eloquent
 */
class Institution extends Model
{
    protected $table='admin_institutions';
    //protected $guarded = [];
    protected $fillable = [
        'name', 'parent_id', 'kind_id'
    ];
    //机构与机构类型1对1
    //public function kind() {
    //    return $this->hasOne(Kind::class);
    //}
    //机构与员工1对多
    //public function employees() {
    //    return $this->belongsToMany(Employee::class,'admin_employee_institution','institution_id','employee_id')->withPivot(['institution_id','employee_id']);
    //}
    //机构与员工1对多  Employee中有 institution_id字段
    public function employees() {
        return $this->hasMany(Employee::class);
    }
    
    //机构对应的user[后台用户表]
    //public function users()
    //{   //$this->hasMany($related, $foreignKey, $localKey)
    //    return $this->belongsToMany(AdminUser::class,'admin_user_institution','institution_id','user_id')->withPivot(['institution_id','user_id']);
    //}

    //机构对应的user[后台用户表]
    public function users()
    {   //$this->hasMany($related, $foreignKey, $localKey)
        return $this->hasMany(AdminUser::class,'user_id');
    }
    

}
