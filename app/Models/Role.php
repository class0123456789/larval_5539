<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Role
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AdminUser[] $users
 * @mixin \Eloquent
 */
class Role extends Model
{
    protected $table='admin_roles';
    //protected $guarded = ['price'];;
    protected $fillable = ['name', 'slug', 'description'];
    //
    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'admin_permission_role','role_id','permission_id')->withPivot(['role_id','permission_id']);
    }
    public function users()
    {
        return $this->belongsToMany(AdminUser::class,'admin_role_user','role_id','user_id')->withPivot(['role_id','user_id']);
    }
    //给角色添加权限  $permission_ids 为permission_id参数 可以为单个id也可以为数组
    public function grantPermission($permission_ids)
    {
        return $this->permissions()->attach($permission_ids);
    }
    
                // 给角色删除权限
    public function deletePermission($permission)
    {
        return $this->permissions()->detach($permission);
    }
    
    //角色中是否有权限 contains 包含
    public function roleHasPermission($permission)
    {
        return $this->permissions()->contains($permission);
    }

}
