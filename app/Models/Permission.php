<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Permission
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @mixin \Eloquent
 */
class Permission extends Model
{
    protected $table='admin_permissions';
    protected $fillable = ['name', 'slug', 'description'];
    //protected $guarded = ['price'];;
    public function roles()
    {
        
        return $this->belongsToMany(Role::class,'admin_permission_role','permission_id','role_id')->withPivot(['permission_id','role_id']);
    }
    public function users()
    {
        return $this->belongsToMany(AdminUser::class,'admin_permission_user','permission_id','user_id')->withPivot(['permission_id','user_id']);
    }
}
