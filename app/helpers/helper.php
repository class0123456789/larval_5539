<?php


/**
 * 清空缓存
 */
//if(!function_exists('cacheClear')){
//	function cacheClear()
//	{
//		cache()->flush();
//                //cache()->forget('menuList');//清理指定缓存
//	}
//}



/**
 * 操作提示信息
 */
if(!function_exists('flash_info')){
	function flash_info($result,$successMsg = 'success !',$errorMsg = 'something error !')
	{
		return $result ? flash($successMsg,'success')->important() : flash($errorMsg,'danger')->important();
	}
}

/**
 * 获取当前用户权限、角色
 */
if(!function_exists('getCurrentPermission')){
	function getCurrentPermission($user)
	{
		$key = 'user_'.$user->id;
		if (cache()->has($key)) {
			return cache($key);
		}
		setUserPermissions($user);
		return cache($key);
	}
}

/**
 * 刷新用户权限、角色
 */
if(!function_exists('setUserPermissions')){
	function setUserPermissions($user)
	{
		//$rolePermissions = $user->rolePermissions()->get()->pluck('slug');
        //得到用户的角色所拥有的所有权限
            //dd($user->roles()->get());
          $allPermissions = \App\Models\Permission::all(['id', 'name', 'slug','description']);//所有的权限
          $user_roles= $user->roles;//得到用户角色
          if($user->id ===1){//id==1 为超级管理员 权限始终为全部权限
               //dump($allPermissions->pluck('id'));
                //$cpermissions = $allPermissions->pluck('id');
               //dump($user->permissions);
               // $user->permissions()->detach(); //清除所有的权限
               // $user->permissions()->attach($cpermissions);
                //$check=true;
                //dd('当前是超级管理员');
                
                 $old_permissions=$user->permissions->pluck('id');    //取所有权限的id组成的集合
                 $new_permissions=$allPermissions->pluck('id');
                                         
                 $old_in=$old_permissions->diff($new_permissions);   //原集合中存在，新集合中不存在的,要进行删除
                                        //dd($old_permissions,$new_permissions,$old_in);
                 $new_in=$new_permissions->diff($old_permissions);   //新集合中存在，原集合中不存在的,要进行增加
                 
                  if($old_in->isNotEmpty()){
                     $user->permissions()->detach($old_in);//删除权限
                  }
                  if($new_in->isNotEmpty()){
                     $user->permissions()->attach($new_in);//增加权限
                  }
           }
           if(in_array('admin',$user_roles->pluck('slug')->toarray()))//如果当前的用户包括有admin角色
            {
                $role =\App\Models\Role::where('slug','admin')->first();
                //dd($role);
                $role_old_permissions = $role->permissions->pluck('id');
                $role_new_permissions = $allPermissions->pluck('id');
                
                $old_in=$old_permissions->diff($role_new_permissions);   //原集合中存在，新集合中不存在的,要进行删除
                                        //dd($old_permissions,$new_permissions,$old_in);
                $new_in=$new_permissions->diff($role_old_permissions);   //新集合中存在，原集合中不存在的,要进行增加
                 
                  if($old_in->isNotEmpty()){
                     $role->permissions()->detach($old_in);//删除权限
                  }
                  if($new_in->isNotEmpty()){
                     $role->permissions()->attach($new_in);//增加权限
                  }
                
                //$role->permissions()->detach(); //清除所有的权限
                //$role->permissions()->attach($cpermissions);
               // $check=true;
            }
            
          
        $user_roles_permissions=$user->userRolesPermissions($user_roles);//得到所有角色权限
        $user_permissions = $user->permissions;//取得所有用户的权限
        $permissions = $user_roles_permissions->merge($user_permissions);//得到当前用户的所有权限
        
        
        //$curr_institution_id = $user->institutions()->first()->id;
        
        //dd($permissions,$allPermissions);
        
        

//forever 方法可以用来将缓存项永久存入缓存中，因为这些缓存项不会过期，所以必须通过 forget 方法手动删除：
         //缓存用户权限
        cache()->forever('user_'.$user->id, [
        	'permissions' => $permissions->pluck('slug'),//保存用户权限
        	'roles' =>$user_roles->pluck('slug'),//保存用户角色权限
        	//'allPermissions' => $allPermissions->pluck('slug'),//保存所有权限
             'pid' => $user->institution->id,//保存用户的管理机构号
                //'allInstitutions' =>SetInstitutionCache(),//保存所有机构

        ]);
        //缓存所有权限
        // cache()->forever('allPermissions',$allPermissions->toArray()
//$allPermissions->pluck('slug')
               //);
        //缓存所有机构
        // SetInstitutionCache();
	}
}

if(!function_exists('haspermission')){//Gate::forUser(auth('admin')->user())->check('admin.permission.create')
	function haspermission($permission)
	{
            //cache()->forget('menuList');
        $check = false;
        if (auth('admin')->check()) {
            
            $user = auth('admin')->user();
            //$key = 'user_'.$user->id;
            //cache()->forget($key);
            $userPermissions =  getCurrentPermission($user);
            //dd(in_array('permission', $userPermissions['permissions']->toarray()));
            $check = in_array($permission,$userPermissions['permissions']->toarray());
          
           // if(in_array('admin',$user->roles->pluck('slug')->toarray()))//如果当前的用户是admin角色
           // {
           //     $role =\App\Models\Role::where('slug','admin')->first();
           //     $allpermissions = \App\Models\Permission::all(['id']);
           //     $role->permissions()->detach(); //清除所有的权限
           //     $role->permissions()->detach($allpermissions);
           //     $check=true;
           //}
            
            //为admin角色增加权限
            //if (in_array('admin', (array)$userPermissions['roles']) && !$check) {
            //    $newPermission = \App\Models\Permission::firstOrCreate([
            //        'slug' => $permission,
            //    ],[
            //        'name' => $permission,
            //        'description' => $permission,
            //    ]);
            //    $role = \App\Models\Role::where('slug', 'admin')->first();
            //    $role->givePermissionTo($newPermission);
            //    setUserPermissions($user);
            //    $check = true;
            //}
        }
            return $check;
	}
}



    //[一维数组转树形数组]
if(!function_exists('treeData')){
    function treeData($data,$pid = 0){
        $result = array();
        foreach($data as $v){
            if($v['parent_id'] == $pid){
                $v['child'] = treeData($data,$v['id']);
                $result[] = $v;
            }
        }
        return $result;
    }
}


if(!function_exists('_reSort')){//返回所有的整理后的机构信息[树型转成一维数组]
    function _reSort($data,$pid=0, $level=0, $isClear=TRUE)
    {
        static $ret = array();
        if($isClear)
            $ret = array();
        foreach ($data as $k => $v)
        {
            //if($v['parent_id'] == $pid)
              //{
                //$v->level = $level;
                $ret[] = ['id'=>$v['id'],'name'=>$v['name'],'level'=>$level,'parent_id'=>$v['parent_id']];
                if($v['child']){
                    _reSort($v['child'], $v['id'], $level+1, FALSE);
                }
                //$ret[] = $v;
                //_reSort($data, $v['id'], $level+1, FALSE);
            //}


                
            
        }
        return $ret;
    }
}

if(!function_exists('_children')){
    //根据所有的整理后的机构信息
    //返回指定机构的及子级机构信息
    function _children($data, $id=0,$isClear=TRUE)
    {
        static $ret = array();
        if($isClear)
            $ret = array();
        foreach ($data as $k => $v)
        {
            if($v['parent_id']== $id)
            {
                //$v->level = $level;
                 $ret[] = ['id'=>$v['id'],'name'=>$v['name'],'level'=>$level,'parent_id'=>$v['parent_id']];
                //$ret[] = $v;
                _children($data, $v['id'], FALSE);
            }
        }
        return $ret;
    }
}

if(!function_exists('_subchildren')){
    //根据所有的整理后的机构信息
    //取得所有子级机构信息 结果如: "5,6,7,9,16,18,19,21"
    function _subchildren($data, $pid=0, $isClear=TRUE)
    {
        static $ret = '';
        if($isClear)
            $ret = '';
        foreach ($data as $k => $v)
        {
            if($v['parent_id'] == $pid)
            {
                $ret= $ret.','.$v['id'];
                _subchildren($data, $v['id'], FALSE);
            }
        }

        if(strpos($ret,',')===0){
            $ret=substr($ret,1,strlen($ret)-1);
        }
        return $ret;
    }
    



if(!function_exists('getUser')){
	function getUser($guards='')
	{
		return auth($guards)->user();
	}
}

if(!function_exists('getUserId')){
	function getUserId($guards)
	{
		return getUser($guards)->id;
	}
}

if(!function_exists('setRow_No')){//在数组中增加row_no字段
	function setRow_No($array)
	{
            foreach($array as $k=>$v){
                  $array[$k]['row_no']= $k+1;
            }
	    return $array;
	}
}
if(!function_exists('getAllPermissions')){//获取机构
 function getAllPermissions()
 {
           //dump(cache('allPermissions'));
           //return cache('allPermissions');

     // 判断数据是否缓存
     if (!cache()->has('allPermissions')) {
         SetallPermissionCache();
     }

     return cache()->get('allPermissions');
 }
    
}

if(!function_exists('SetallPermissionCache')){//设置所有机构的缓存
    function SetallPermissionCache()
	{
        $permissions=\App\Models\Permission::all(['id', 'name', 'slug','description'])->toArray();
        $array=[];
        if ($permissions) {
            foreach ($permissions as $v) {
                if(strpos($v['slug'],'.')){
                    $temp = explode('.', $v['slug']); //点分隔的命名路由权限
                }elseif (strpos($v['slug'],'/')) {
                    $temp = explode('/', $v['slug']);//斜扛分隔的没有命名路由权限 或者跳转  这里一般是自定义的路由
                }elseif (strpos($v['slug'],'::')) {//双冒号分隔的命名路由权限 或者跳转
                    $temp = explode('::', $v['slug']);
                }


                $array[$temp[0]][] = $v;
            }
        }
            cache()->forever('allPermissions', $array);
           //return  \App\Models\Permission::all(['id', 'name', 'slug','description'])->toArray();
	}
    
}
// 获取菜单数据
if(!function_exists('getMenuList')) {// 获取菜单数据
    function getMenuList()
    {
        // 判断数据是否缓存
        if (!cache()->has('menuList')) {
            SetMenuCache();
        }

            return cache()->get('menuList');


    }
}
if(!function_exists('SetMenuCache')){//// 缓存菜单数据
        function SetMenuCache()
        {
            //cache()->forever('menuList' ,\App\Models\Institution::all()->toArray());
            //return  \App\Models\Institution::all()->toArray();
            $menus = \App\Models\Menu::all()->toArray();
            $menuList=[];
            if($menus){
                $menuList = sortMenu($menus);
                foreach ($menuList as $key => &$v) {
                    if ($v['child']) {
                        $sort = array_column($v['child'], 'sort');
                        array_multisort($sort,SORT_DESC,$v['child']);
                    }
                }


            }
            // 缓存菜单数据
            cache()->forever('menuList',$menuList);
            //return $menuList;

        }

    }
//获取指定父级机构的所有子级机构后 进行排序并缓存
//child 表示子级机构
if(!function_exists('sortMenu')) {//设置所有机构的缓存
        function sortMenu($menus, $pid = 0)
        {
            $arr = [];
            if ($menus) {
                foreach ($menus as $key => $v) {
                    if ($v['pid'] == $pid) {
                        $arr[$key] = $v;
                        $arr[$key]['child'] = sortMenu($menus, $v['id']);
                    }
                }
            }
            return $arr;
        }
}

//设置所有菜单的缓存
//    if(!function_exists('cacheMenuClear')) {
//        function cacheMenuClear()
//        {
//            cache()->forget('menuList');
//            //SetMenuCache();
//
//        }
//    }

//设置所有菜单的缓存
if(!function_exists('cacheClear')) {
        function cacheClear($cachekey)
        {

            //cache()->forget('allInstitutions');
            if (cache()->has($cachekey)) {
                //SetMenuCache();
                cache()->forget($cachekey);
            }
            //cache()->forget($cachekey);
            //SetInstitutionCache();

        }
}
//设置所有机构的缓存
if(!function_exists('SetInstitutionCache')){
    function SetInstitutionCache()
	{
           cache()->forever('allInstitutions' ,\App\Models\Institution::all()->toArray()); 
           //return  \App\Models\Institution::all()->toArray();
	}
    
}


if(!function_exists('getInstitutionList')){//获取机构
    function getInstitutionList($pid=1)
	{
        // 判断数据是否缓存
        if (!cache()->has('allInstitutions')) {
            SetInstitutionCache();
        }

        return sortInstitution(cache()->get('allInstitutions'),$pid);
	}
    
}



//获取指定父级机构的所有子级机构后 进行排序并缓存 
//child 表示子级机构
if(!function_exists('sortInstitution')){//获取指定父级机构的所有子级机构后 进行排序并缓存 
    function sortInstitution($institutions,$pid=1)
	{
                 //dd($institutions);
                 //cache()->forever('institutionList',$institutionList);
                 //$institutions = \App\Models\Institution::all()->toArray();
                if ($institutions) {
                        //$pid=getCurrentPermission(getUser('admin'))['pid'];
                        //$curr_institution= \App\Models\Institution::find($pid)->toArray();
                        $admin_curr_institution =_findInstitution($institutions,$pid);//在原始数据记录中取id=pid的机构
			            $institutionList = _childInstitution($institutions,$pid);//取pid的子级,机构整理

                        if($admin_curr_institution){
                            if($institutionList){
                                //dump($institutionList);
                                $admin_curr_institution[0]['child']=$institutionList ;
                                $admin_curr_institution[0]['level']= 0 ;
                                 //dd($admin_curr_institution,head($admin_curr_institution));
                                $institutionList=recursion_orderby($admin_curr_institution);//递规进行排序
                                //dump($admin_curr_institution,head($admin_curr_institution),$institutionList);
                                unset($admin_curr_institution);
                               //  unset($admin_curr_institution,$institutions);
                                //dd($institutionList);
                                // 缓存机构数据
                                //cache()->forever('institutionList',$institutionList);
                                return $institutionList;
                            }else{
                                $admin_curr_institution[0]['child']=[] ;
                                $admin_curr_institution[0]['level']= 0 ;
                                return $admin_curr_institution;
                            }
                            //dd($admin_curr_institution,$institutionList);

                        }
                        //$arr=[];
                        
                        //$arr[0]['child'] = $institutionList;
                        //dd($arr);
                        //$curr_institution['child'] = $institutionList; 
                        //$arr[] = $curr_institution;
                        //dump($institutionList,$curr_institution);
                        //$institutionList= $arr; 
                         //dd($institutionList,$curr_institution);
                        //unset($curr_institution,$pid,$arr);
//			foreach ($institutionList as $key => &$v) {
//				if ($v['child']) {
//					$sort = array_column($v['child'], 'id');
//                                        
//					array_multisort($sort,SORT_ASC,$v['child']);
//				}
//			}
//			
			
			
		}
                //unset($institutions);
		return [];
	}
    
}

//机构整理成带有child字段的数组结构,递归整理
if(!function_exists('_childInstitution')){//递归
        function _childInstitution($institutions,$pid=1)
	{
                $arr = [];
		if (empty($institutions)) {
			return $arr;
		}
		foreach ($institutions as $key => $v) {
                        //if($v['id'] == $pid){
                        //    $arr[$key] = $v;
                        //    $arr[$key]['child']= self::sortInstitution($institutions,$v['id']);
                        //}
			if ($v['parent_id'] == $pid) {
				$arr[$key] = $v;
				$arr[$key]['child'] = _childInstitution($institutions,$v['id']);
			}
		}
		return $arr;
	}
    
}


if(!function_exists('_findInstitution')){//获取指定的机构信息 ,返回的是原始array的1条数据 不带child,level 字段
    function _findInstitution($institutions,$id=1)
	{
                $arr = [];
		if(empty($institutions)) {
			return [];
		}
		foreach ($institutions as $key => $v) {
                        //if($v['id'] == $pid){
                        //    $arr[$key] = $v;
                        //    $arr[$key]['child']= self::sortInstitution($institutions,$v['id']);
                        //}
			if ($v['id'] == $id) {
				$arr[] = $v;
                                break 1; //后面1是跳出for循环的层数
				//$arr[$key]['child'] = _childInstitution($institutions,$v['id']);
                            
                        }
		}
		return $arr;
	}
    
}


/**
 * 递归根据特定key对数组排序,并增加了level级别层次字段
 * @param $data
 * @param string $orderKey
 * @param string $sonKey
 * @param int $orderBy
 * @return mixed
 */
if(!function_exists('recursion_orderby')){
        function recursion_orderby($data, $orderKey = 'id', $sonKey = 'child', $orderBy = SORT_ASC,$level=1)
        {
            //$sort = array_column($v['child'], 'id');                               
            //array_multisort($sort,SORT_ASC,$v['child']);
            $func = function ($value) use ($sonKey, $orderKey, $orderBy,$level) {
                $value['level'] = $level;
                if (isset($value[$sonKey]) && is_array($value[$sonKey])) {
                    
                    $value[$sonKey] = recursion_orderby($value[$sonKey], $orderKey, $sonKey, $orderBy,$level+1);
                }
                return $value;
            };
            return array_orderby(array_map($func, $data), $orderKey, $orderBy);
            //$sorted = array_orderby($data, 'volume', SORT_DESC, 'edition', SORT_ASC);//设置升降序字段
        }
}

//排序function_
if(!function_exists('array_orderby')){
        function array_orderby()
        {
            $args = func_get_args();
            $data = array_shift($args);
            foreach ($args as $n => $field) {
                if(is_string($field)) {
                    $tmp = array();
                    foreach ($data as $key => $row)
                    {
                       $tmp[$key] = $row[$field];
                    }
                    $args[$n] = $tmp;
                }
            }
            $args[] = &$data;
            call_user_func_array('array_multisort', $args);
            return array_pop($args);
        }
}


//删除数组中的一个元素
if(!function_exists('_deleteChildInstitution')){//返回删除指定id机构后的信息 ,返回的是array数据 带child,level 字段
    function _deleteChildInstitution(&$institutions,$id=1)
	{
            if(count($institutions)>0 && is_array($institutions)){
                foreach($institutions as $k=>$v){
                    if($v['id'] == $id){
                        array_splice($institutions,$k,1);
                        //dump($institutions);
                        break 1;
                    }else{
                        _deleteChildInstitution($institutions[$k]['child'],$id);
                    }
                }
            }

	}
    
}


//递归获取所有的id 返回的是一个一级的array
//默认取ID 如果设置了就取设置的值
//如果没有设置的值就取k值
if(!function_exists('_getAllInstitutionId')){//返回删除指定id机构后的信息 ,返回的是array数据 带child,level 字段
        function _getAllInstitutionId($institutions,$colName='id')
	{
            static $ret = [];
            if(count($institutions)>0 && is_array($institutions)){
                foreach($institutions as $k=>$v){
                            if(isset($v[$colName])){
                                $ret[] = $v[$colName];
                            }else{
                                $ret[] = $k;
                            }

                            if(isset($v['child']) && $v['child']){
                                _getAllInstitutionId($v['child']);
                            }
                    
                        
                    
                }
            }
            return $ret;

	}
    
}



//递归获取所有的id,name  id=>name 行式的一维数组
    if(!function_exists('_getAllInstitutionIdNameLevel')){//返回删除指定id机构后的信息 ,返回的是array数据 带child,level 字段
        function _getAllInstitutionIdNameLevel($institutions)
        {
            static $ret = [];
            if(count($institutions)>0 && is_array($institutions)){
                foreach($institutions as $k=>$v){

                    //$ret[]=array_add(array(),$v['id'],$v['name']);
                    //$ret[]=array_add([],$v['id'],$v['name']);
                    $ret[$v['id']]=["name"=>$v['name'],"level"=>$v['level']];
                    //$ret[$v['id']]=$v['name'];
                    //dump($ret);
                     if(count($v['child'])){
                         $ret[$v['id']]=["name"=>$v['name'],"level"=>$v['level'],"haschild"=>true];
                         //dump('child');
                        _getAllInstitutionIdNameLevel($v['child']);
                    }else{
                         $ret[$v['id']]=["name"=>$v['name'],"level"=>$v['level'],"haschild"=>false];
                     }



                }
            }
            return $ret;

        }

    }




/**
 * 获取当前用户可管理的父级机构id(admin后台用户获取)
 */
if(!function_exists('getCurrentInstitutionId')){
        function getCurrentInstitutionId(){
              $pid=getCurrentPermission(getUser('admin'))['pid'];//得到当前父级pid
              return $pid;
        }
	
}





	

	
	


}