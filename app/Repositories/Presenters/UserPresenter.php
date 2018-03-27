<?php
namespace App\Repositories\Presenters;

class UserPresenter {
	
	/**
	 * 创建修改用户界面，角色权限列表
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $permissions     [description]
	 * @param  array      $rolePermissions [description]
	 * @return [type]                      [description]
	 */
	public function permissionList($permissions,$rolePermissions=[])
	{
		$html = '';
		if ($permissions) {
			foreach ($permissions as $key => $permission) {
				$html .= "<tr><td>".$key."</td><td>";
				if (is_array($permission)) {
					foreach ($permission as $k => $v) {
						$html .= <<<Eof
						<div class="col-md-4">
	                     	<div class="i-checks">
	                        	<label> <input type="checkbox" name="permission[]" {$this->checkPermisison($v['id'],$rolePermissions)} value="{$v['id']}"> <i></i> {$v['name']} </label>
	                      	</div>
                      	</div>
Eof;
					}
				}
				$html .= '</td></tr>';
			}
		}
		return $html;
	}
	/**
	 * 添加用户出现错误时，获取已经选中的选项
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $permissionId    [description]
	 * @param  array      $rolePermissions [description]
	 * @return [type]                      [description]
	 */
	private function checkPermisison($permissionId,$rolePermissions = [])
	{
		$permissions = old('permission');
		if ($permissions) {
			return in_array($permissionId,$permissions) ? 'checked="checked"':'';
		}
		if ($rolePermissions) {
			if ($permissions) {
				if (in_array($permissionId,$rolePermissions) && in_array($permissionId,$permissions)) {
					return 'checked="checked"';
				}
			}else{
				return in_array($permissionId,$rolePermissions) ? 'checked="checked"':'';
			}
			return '';
		}
		return '';
	}
	/**
	 * 角色列表
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $roles     [description]
	 * @param  array      $userRoles [description]
	 * @return [type]                [description]
	 */
	public function roleList($roles,$userRoles=[])
	{
		$html = '';
		if (!$roles->isEmpty()) {
			foreach ($roles as $role) {
                //$html .= '<label class="checkbox-inline"><div class="i-checks"><label> <input type="checkbox" name="role[]" '.$this->checkRole($role->id,$userRoles).' value="'.$role->id.'"> '.$role->name.' [<a data-target="#myModal" data-toggle="modal" href="'.route('role.show', [encodeId($role->id, 'role')]).'">'.trans('user.role_info').'</a>]</label></div></label>';
				$html .= '<label class="checkbox-inline"><div class="i-checks"><label> <input type="checkbox" name="roles[]" '.$this->checkRole($role->id,$userRoles).' value="'.$role->id.'"> '.$role->name.' [<a data-target="#myModal" data-toggle="modal" href="'.route('role.show', $role->id).'">角色权限</a>]</label></div></label>';
			}
		}
		return $html;
	}
	/**
	 * 添加用户出现错误时，获取已经选中的角色
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $roleId    [description]
	 * @param  array      $userRoles [description]
	 * @return [type]                [description]
	 */
	public function checkRole($roleId,$userRoles = [])
	{
		$roles = old('roles');
                //if (!$roles) {$roles=[];}
                //dd($roles);
		if ($roles) {
			return in_array($roleId,$roles) ? 'checked="checked"':'';
		}
		if ($userRoles) {
			if ($roles) {
				if (in_array($roleId,$userRoles) && in_array($roleId,$roles)) {
					return 'checked="checked"';
				}
			}else{
				return in_array($roleId,$userRoles) ? 'checked="checked"':'';
			}
			return '';
		}
		return '';
	}
	/**
	 * 查看用户信息时展示的table
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $userPermissions [description]
	 * @return [type]                      [description]
	 */
	public function showUserPermissions($userPermissions)
	{
		$html = '';
		if (!$userPermissions->isEmpty()) {
			// 将角色权限分组
			$permissionArray = [];
			foreach ($userPermissions as $v) {
                 if(strpos($v->slug,'.')){
                       $temp = explode('.', $v->slug); //点分隔的命名路由权限 
                     }elseif (strpos($v->slug,'/')) {
                        $temp = explode('/', $v->slug);//斜扛分隔的没有命名路由权限 或者跳转  这里一般是自定义的路由
                     }elseif (strpos($v->slug,'::')) {//双冒号分隔的命名路由权限 或者跳转  
                        $temp = explode('::', $v->slug);  
                      }
                $permissionArray[$temp[0]][] = $v->toArray();
            }
			if ($permissionArray) {
				foreach ($permissionArray as $key => $permission) {
					$html .= "<tr><td>".$key."</td><td>";
					if (is_array($permission)) {
						foreach ($permission as $k => $v) {
							$html .= <<<Eof
							<div class="col-md-4">
	                        	<label> {$v['name']} </label>
	                      	</div>
Eof;
						}
					}
					$html .= '</td></tr>';
				}
			}
		}
		return $html;
	}
	/**
	 * 查看用户信息时展示的角色
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $userRoles [description]
	 * @return [type]                [description]
	 */
	public function showUserRoles($userRoles)
	{
		$html = '';
		if (!$userRoles->isEmpty()) {
			foreach ($userRoles as $role) {
				$html .= '<label class="checkbox-inline">'.$role->name.' [<a data-target="#myModal" data-toggle="modal" href="'.route('role.show', $role->id).'">角色权限</a>]</label>';
			}
		}
		return $html;
	}

        //$pid 是设置默认选中id值
   	public function topInstitutionList($institutions,$pid=0)
	{
            //dd($institutions);
		//$html = '<option value="0">顶级机构</option>';
                $html='';
                //if($isClear){ //是否第一次
                //    $html = '<option value="0">顶级机构</option>';
                //}
		if ($institutions) {
                    foreach ($institutions as $v) {
          
                                    $html .= '<option value="'.$v['id'].'" '.$this->checkInstitution($v['id'],$pid).'>'.str_repeat("&nbsp;&nbsp;",$v['level']).$v['name'].'</option>';
			        
                                    if($v['child']){
                                        $html .=self::topInstitutionList($v['child'],$pid);
                                    }
                                
                                
                        }
                        
		}
		return $html;
	}
	public function checkInstitution($institutionId,$pid)
	{
		//if ($pid !== 0) {
			if (intval($institutionId) == intval($pid)) {
				return 'selected="selected"';
                               // \dump('true');
			}
			return '';
		//}
		//return '';
	}


}