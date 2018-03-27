<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;


/**
 * App\Models\AdminUser
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @mixin \Eloquent
 */
class AdminUser extends Authenticatable
{
    use Notifiable;
    protected $table='admin_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password','institution_id'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    //用户角色
    public function roles()
    {
        /*
         * Role::class 关联模型
         * admin_role_user 关联表
         * user_id 当然模型 外键
         * role_id关联模型 外键
         */
        return $this->belongsToMany(Role::class,'admin_role_user','user_id','role_id')->withPivot(['user_id','role_id']);
    }
    // 判断用户是否具有某个角色,某些角色
    public function isInRoles($roles)
    {
        //if (is_string($role)) {
        //    return $this->roles->contains('name', $role);
        //}
        //intersect取两个集合的交集
        return !!$roles->intersect($this->roles)->count();
    }

    // 给用户分配角色
    public function assignRole($role)
    {
        return $this->roles()->save($role);
    }
    
        // 给用户分配角色 删除user和role的关联
    public function deleteRole($role)
    {
        return $this->roles()->detach($role);
    }
    
        // 给用户分配权限
    public function assignPermission($permission)
    {
        return $this->permissions()->save($permission);
    }
    
            // 给用户删除权限
    public function deletePermission($permission)
    {
        return $this->permissions()->detach($permission);
    }
    //角色整体添加与修改
    public function giveRoleTo(array $RoleId){
        $this->roles()->detach();
        $roles=Role::whereIn('id',$RoleId)->get();
        foreach ($roles as $v){
            $this->assignRole($v);
        }
        return true;
    }
    
    //用户权限
    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'admin_permission_user','user_id','permission_id')->withPivot(['user_id','permission_id']);
    }
    
    //角色中是否有权限 contains 包含
    public function userHasPermission($permission)
    {
        return $this->permissions->contains($permission);
    }
    
    //返回用户所有角色中包括的权限
    public function userRolesPermissions($roles)
    {
        $permissions = \collect();
        $roles->each(function ($item, $key) use (&$permissions) {
            //dump($item);dump( $collection);
      
            //dd($item->permissions);
                  $permissions=$item->permissions->merge($permissions);
                  //dump($collection);
        });
        //
        return $permissions;
    }
    
    
    //判断用户是否具有某权限
    public function hasPermission($permission)
    {
        //if (is_string($permission_name)) {
        //    $permission = Permission::where('slug',$permission_name)->first();//取指定的权限
        //    if (!$permission) return false;
        //    $check = $this->hasRole($permission->roles)||in_array($permission, (array)$this->permissions()->get()->pluck('slug'));
            
         //   return $check;
       // }
        //dump($this->isInRoles($permission->roles));
        //dump($this->userHasPermission($permission));
        $check = $this->isInRoles($permission->roles)||$this->userHasPermission($permission);
        //dump($check);
       //    $check = $this->userRolesPermissions($permission->roles)||$this->userHasPermission($permission);
        
        return $check;
    }
    
    //用户管理机构
    //public function institutions()
    //{   //$this->hasMany($related, $foreignKey, $localKey)
    //    return $this->belongsToMany(Institution::class,'admin_user_institution','user_id','institution_id')->withPivot(['user_id','institution_id']);
    //}

    //用户管理机构
    public function institution()
    {   //$this->hasMany($related, $foreignKey, $localKey)
        return $this->belongsTo(Institution::class);
    }
    


}
