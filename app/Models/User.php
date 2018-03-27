<?php

namespace App\Models;


use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;



/**
 * App\Models\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable,HasApiTokens;
    protected $table='users';
    /**
     * The attributes that are mass assignable.
     *protected $guarded = ['price'];
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','employee_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    

    
    //取得员工
    //public function employee()
    //{
    //    return $this->belongsToMany(Employee::class,'user_empoyee','user_id','empoyee_id')->withPivot(['user_id','empoyee_id']);
    //}
    
        
    //取得员工 才能取得此员工所管理的机构
    public function employee()//返向关联 本模型中要用employee_id字段
    {
        return $this->belongsTo(Employee::class);
    }
    

}

