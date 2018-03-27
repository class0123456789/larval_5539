<?php

namespace App\Http\Controllers\Admin;

use App\Events\permChange;
use App\Models\User;
use App\Models\Employee;
use App\Models\Institution;
use Illuminate\Http\Request;
use App\Http\Requests\FuserRequest;
use App\Http\Requests;
use App\Api\Helpers\Api\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Cache,Event;

class FuserController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        
        //$g_institutions = $this->getInstitutionList();//得到所有机构
        $currinstitutions=$this->getCurrInstitutions();
        //dd($currinstitutions);
        $arr = _getAllInstitutionId($currinstitutions);
        //dd($arr);
        
        
        //$pid=getCurrentPermission(getUser('admin'))['pid'];
        //$curr_institution= \App\Models\Institution::find($pid)->toArray();
                        //dd($pid,$curr_institution);
        //$in=_reSort($g_institutions,$pid);//返回一维数组格式数据 所有机构
        //dd($in);
        //$id_name = array_pluck($in, 'name', 'id'); //转换成以id值为键,name为值的一维数组
                        //dd($array);
                        //$in=_reSort($institutions->toArray(),$pid);
         //$g_in_s=  $pid.','._subchildren($in,$pid);//"2,8,9"
          $g_in_s = implode(',',$arr);
         

         //$g_in=explode(',', $pid.','._subchildren($in,$pid));//保存所有的机构id 数组保存
         
          //$array = array_where($id_name, function ($value, $key) use ($g_in){
          //   if(in_array($key,$g_in)) return $value;
         //});
         //dd($array);
         
       
        

         //dd($arr);
         //dd($g_institutions);
        //查询操作员语句
        
         //$mysql = "SELECT @row_no:=@row_no+1 as row_no, a.id ,a.name,a.email ,CONCAT('[',a.employee_id ,']',b.name) as employee_name,CONCAT('[',c.institution_id,']',d.name) as institution_name  FROM users a ,(select  @row_no:=0) t  ,admin_employees b,admin_employee_institution c,admin_institutions d where a.employee_id=b.id and b.id=c.employee_id and c.institution_id=d.id and d.id in(".$g_in_s.") ";
         //$countmysql = "select count(id) as total from  (". $mysql. ") z";
         
         $mysql = "SELECT @row_no:=@row_no+1 as row_no, a.id ,a.name,a.email ,CONCAT('[',a.employee_id ,']',b.name) as employee_name,CONCAT('[',b.institution_id,']',c.name) as institution_name  FROM users a ,(select  @row_no:=0) t  ,admin_employees b,admin_institutions c where a.employee_id=b.id and b.institution_id=c.id  and c.id in(%s) ";
         $countmysql = "select count(id) as total from  (%s) z";
         //$cout = DB::select($countmysql);
         //dd(array_shift($cout)->total);
         
         //dd(DB::select($mysql));
         
         
           // $users = DB::table('users')
           // ->join('admin_employees', 'users.employee_id', '=', 'admin_employees.id')
           // ->join('admin_employee_institution', 'admin_employee_institution.employee_id', '=', 'admin_employees.id')     
           // ->join('admin_institutions', 'admin_institutions.id', '=', 'admin_employee_institution.institution_id')        
           // ->select('users.id','users.name','users.email','users.employee_id','admin_employees.name as employee_name', 'admin_employee_institution.institution_id', 'admin_institutions.name as institution_name')
           // ->whereIn('admin_institutions.id',$g_in)  
           //->get();
          //dd($users);

         //   $user = User::with('employee')->find(1);
         //   dump($user->employee);//返回employee对象
         //   $u= $user->toArray();//返回employee对象
         //   dump($user->employee->institutions()->first()->toarray());
         //   $u['institution'] = $user->employee->institutions()->first()->toarray();
         //   dd( $u);//返回institution对象的集合
        
            //dd($user->employee->institutions);//返回institution对象的集合
         
                        //$data['data'] = DB::table('users')
            //->join('admin_employees', 'users.employee_id', '=', 'admin_employees.id')
            //->join('admin_employee_institution', 'admin_employee_institution.employee_id', '=', 'admin_employees.id')     
            //->join('admin_institutions', 'admin_institutions.id', '=', 'admin_employee_institution.institution_id')        
            //->select('@rowno:=@rowno+1 as rowno','users.id','users.name','users.email','users.employee_id','admin_employees.name as employee_name', 'admin_employee_institution.institution_id', 'admin_institutions.name as institution_name')
            //->whereIn('admin_institutions.id',$g_in)
            //->skip(0)->take(10)
            
            //->get()->all();
                         
                //$data['data'] = User::skip(0)->take(10)
                   
                //   ->get();
                         
                         //dd($data['data'] );
         
         
         
         
         
        if ($request->ajax()) {
            //1 取得当前用户可以操作的机构
          
            //2 查询出 在上面查询出的机构的中 所有员工
            //3 查询出 上面得出的员工 对应的 操作员
            //$users = DB::select('select * from users where active = ?', [1]);
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $order = $request->get('order');
            $columns = $request->get('columns');
            $search = $request->get('search');
            if(!isset($request['jgh'])){
                $cid=0;
            }else{
                $cid = $request['jgh'];
            }
            
            if($cid==0){
              //$cid = 0; //全部  
              $mysql = sprintf($mysql,$g_in_s);
              $countmysql = sprintf($countmysql,$mysql);
            }else{
             //$cid = $request->get('jgh');
             $mysql = sprintf($mysql,$cid);
             $countmysql = sprintf($countmysql,$mysql);
            
            }
            $cout = DB::select($countmysql);
            $data['recordsTotal'] =array_shift($cout)->total;

            if (strlen($search['value']) > 0) {
                //$data['recordsFiltered'] = User::where(function ($query) use ($search) {
                //    $query->where('name', 'LIKE', '%' . $search['value'] . '%')
                //        ->orWhere('email', 'like', '%' . $search['value'] . '%');
                //})->count();
                $where = " and ((a.name like '%".$search['value']."%' ) or (a.email  like '%".$search['value']."%')  or (b.name  like '%".$search['value']."%') or (c.name  like '%".$search['value']."%')) ";
                $mysql = $mysql . $where;
                $countmysql = "select count(id) as total from  (". $mysql. ") z";
                $cout = DB::select($countmysql);
                $data['recordsFiltered']=array_shift($cout)->total;
                $limit = " limit " . $start .", ". $length;
                $orderby = " order by ".$columns[$order[0]['column']]['data'] . " ". $order[0]['dir'];
                    if(intval($length)==-1){
                    $mysql = $mysql.$orderby;
                    $data['data'] =DB::select($mysql );                      
                    }else{
                        $mysql = $mysql.$orderby. $limit;
                        $data['data'] = DB::select($mysql );
                    }
                
                
                
                
                        
                //$data['data'] = User::where(function ($query) use ($search) {
                //    $query->where('name', 'LIKE', '%' . $search['value'] . '%')
                //        ->orWhere('email', 'like', '%' . $search['value'] . '%');
                //})
                //    ->skip($start)->take($length)
                //    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                //    ->get();
                
                
            //->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
            // ->get()->all();        
            } else {
                //$data['recordsFiltered'] = User::count();
                //$data['data'] = User::skip($start)->take($length)
                //    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                //    ->get();
                $data['recordsFiltered'] = $data['recordsTotal'];
                
                //$where = " and (name like %".$search['value']."%  or email  like %".$search['value']."%  or employee_name  like %".$search['value']."% or institution_name  like %".$search['value']."% ) ";
                //$mysql = $mysql . $where;
                //$countmysql = "select count(id) as total from  (". $mysql. ") z";
                //$data['recordsFiltered']=array_shift(DB::select($countmysql))->total;
                $limit = " limit " . $start .", ". $length;
                $orderby = " order by ".$columns[$order[0]['column']]['data'] . " ". $order[0]['dir'];
                if(intval($length)==-1){
                    $mysql = $mysql.$orderby; 
                    $data['data'] =DB::select($mysql);
                }else{
                    $mysql = $mysql.$orderby. $limit; 
                    $data['data'] =DB::select($mysql);
                }
                //$mysql = $mysql.$orderby. $limit;
               
            }
            //dd(response()->json($data));
             //$data['data'] = setRow_No($data['data']);
             return response()->json($data);
        }
            //1 $cout = DB::select($countmysql);
            //$data['recordsTotal'] =array_shift($cout)->total;

            //2  $data['recordsTotal'] = $data['recordsFiltered']=array_shift($cout)->total;;
            //3  $data['data'] = DB::select($mysql);
            //$data['data'] = setRow_No($data['data']);
        
       // $currinstitutions=$this->getCurrInstitutions();
        //dd($currinstitutions);
        return view('admin.fuser.index',compact('currinstitutions'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {           
                //$institutions = $this->getInstitutionList();
                $institutions = $this->getCurrInstitutions();

                $institutionId=getCurrentInstitutionId();//获取当前用户的管理机构id
        //$institutions = getInstitutionList($institutionId);
        //dd($institutions);
                //$pid=getCurrentPermission(getUser('admin'))['pid'];
                $employees =Institution::find($institutionId)->employees->toarray();
                $employees = array_pluck($employees, 'name','id');
                //dd($employees);
                //$permissions = $this->getAllPermissions();
		//$roles = $this->getAllRole();


                return view('admin.fuser.create', compact(['institutions','employees','pid']));
        
        
        

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(FuserRequest $request)
    {
        $request['password']=bcrypt($request['password']);
        //$password = bcrypt($request->get('password'));
        $user = User::create($request->all());
         //dd($user->institutions());

        //$user->save();
        //if (isset($request['roles']) && is_array($request['roles']) ) {
            //$user->roles()->detach();
        //    $user->roles()->attach($request['roles']);
        //}
                //$user->save();
        //if (isset($request['permission']) && is_array($request['permission']) ) {
        //    $user->permissions()->attach($request['permission']);
        //}
        //if (isset($request['institution_id']) ) {
            //$user->institutions()->attach($request['institution_id']);
        //    $user->institutions()->attach($request['institution_id']);;
        //}
        flash_info($user,'前台用户增加成功','前台用户增加失败');
        //Event::fire(new permChange());
        //event(new \App\Events\userAction('\App\Models\User', $user->id, 1, '添加了前台用户' . $user->name));
        return redirect('/admin/fuser');
    }
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
			$user = User::with('employee')->find($id);
                        //dd($user->institutions()->first());
                        //$institution = \App\Models\Institution::find(getCurrentPermission($user)['pid']);
                        //dd($institutions);
			
                         //return view('admin.user.show',compact('roles','permissions','institutions','user'));
                        return view('admin.fuser.show',compact('user'));
		} catch (Exception $e) {
			flash($e->getMessage(), 'danger');
			redirect('/admin/fuser');
		}
        //return view('admin.user.show')->with($result);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
       try {
           $user = User::with('employee')->find($id);
           //创建个人访问令牌 个人访问令牌是永久有效的，就算使用了 tokensExpireIn 和 refreshTokensExpireIn 方法也不会修改它的生命周期。
           //$token = $user->createToken($user->name,[])->accessToken;//accessToken
           //dd($token);

           //$user = User::with('employee')->find(20);
           //if($user['employee']){
               //dd($user['employee']->institutions);
           //}else{
           //    dd($user['employee']);
           //}

                //$user['employee']   由于使用了with 已经查出结果来了，就可以不查数据库了
                //$user->employee   员工对象
                //$user->employee->id 员工id字段
                //$user->employee->institutions->first() 第一个institution对象
                //$user->employee->institutions->first()->id 机构id字段
                //dd($user->employee->institutions->first()->id);
                
                
                $institutions = $this->getCurrInstitutions();//取得当前本后台用户 可操作的所有机构
                //得到当前的默认机构
                //$pid=getCurrentPermission(getUser('admin'))['pid'];
                //  $user->employee->institutions;
               // $d_institution_id = $user->employee->institutions->first()->id;//得到对应用前台用户的机构号
           //dd($user->employee->institution->id);
            //    $d_institution_id = $user->employee->institution->id;//得到对应用前台用户的机构号
               // $employees =Institution::find($d_institution_id)->employees->toarray();
           $employees =$user->employee->institution->employees->toarray();
           //dd($employees);
                $employees = array_pluck($employees, 'name','id');
                
                

                        //with预加载
			//$user = User::find($id);
                        //$institutions = $this->getInstitutionList();
                        //dd($user->institutions()->first()->id);
                        //dd($institution);
                        //dd($role->permissions);
			//$permissions = $this->getAllPermissions();
                        //$roles = $this->getAllRole();
			//return compact('role', 'permissions');
                        return view('admin.fuser.edit',compact('user','institutions','employees'));
	} catch (Exception $e) {
			flash($e->getMessage(), 'danger');
			return redirect('/admin/fuser');
	}
        
        

        //event(new \App\Events\userAction('\App\Models\AdminUser', $user->id, 3, '编辑了用户' . $user->name));
        //return view('admin.user.edit', $data);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(FuserRequest $request,USER $user)
    {
                //dd($request->route('fuser'));
        
        
        
                try { 
                        $id=$request->route('fuser');
                        if(isset($request['password']) && is_string($request['password']) && (strlen($request['password'])>0)){
                            $request['password']=bcrypt($request['password']);
                        }else{
                           unset($request['password']) ;
                        }
                        unset($request['_token']) ;
                        unset($request['institution_id']) ;
                        unset($request['_method']) ;
                        
                        //dump($request,$user,$request['id']);

                        $result=User::where('id',$id)->update($request->all());
                        //dd($result);
                        // dd(  $request->all());
			//$result=$user->update($request->all());
                        //dd($result);
                        //dd($result,request['permission']);
                        //$permissions=$request->all(['permission']);
                        //$institution=$request->all(['institution_id']);
                        //dd($institution);
                        //$roles=$request->all(['roles']);
                        //dd($permissions);
//			if ($result) {
//				// 更新角色权限关系
//				if($permissions) {
//                                        $old_permissions=$user->permissions->pluck('id');    //取所有权限的id组成的集合
//                                        $new_permissions=collect($permissions['permission']);//数组转集合
//                                         
//                                        $old_in=$old_permissions->diff($new_permissions);   //原集合中存在，新集合中不存在的,要进行删除
//                                        //dd($old_permissions,$new_permissions,$old_in);
//                                        $new_in=$new_permissions->diff($old_permissions);   //新集合中存在，原集合中不存在的,要进行增加
//                                        //dump($old_permissions,$new_permissions,$old_in,$new_in);
//                                        if($old_in->isNotEmpty()){
//                                            $user->permissions()->detach($old_in);//删除权限
//                                        }
//                                        if($new_in->isNotEmpty()){
//                                            $user->permissions()->attach($new_in);//增加权限
//                                        }
//                                    
//                                    
//                                    //移除角色的所有权限...
//                                        //$user->permissions()->detach();
//                                     //更新角色的权限...    
//					//$user->permissions()->attach($permissions['permission']);
//				}else{
//					//移除角色的所有权限...
//                                        $user->permissions()->detach();
//				}
//                                
//                                if($roles) {
//                                        $old_roles=$user->roles->pluck('id');    //取所有权限的id组成的集合
//                                        $new_roles=collect($roles['roles']);//数组转集合
//                                         
//                                        $old_in=$old_roles->diff($new_roles);   //原集合中存在，新集合中不存在的,要进行删除
//                                        //dd($old_permissions,$new_permissions,$old_in);
//                                        $new_in=$new_roles->diff($old_roles);   //新集合中存在，原集合中不存在的,要进行增加
//                                        //dd($old_permissions,$new_permissions,$old_in,$new_in);
//                                        if($old_in->isNotEmpty()){
//                                            $user->roles()->detach($old_in);//删除角色
//                                        }
//                                        if($new_in->isNotEmpty()){
//                                            $user->roles()->attach($new_in);//增加角色
//                                        }
//                                    
//                                    //移除角色的所有权限...
//                                       // $user->roles()->detach();
//                                     //更新角色的权限...    
//					//$user->roles()->attach($roles['roles']);
//				}else{
//					//移除角色的所有权限...
//                                        $user->roles()->detach();
//				}
//				cacheClear();
//                                if($institution) {//更新管理机构
//                                        $old_institutions=$user->institutions->pluck('id');    //取所有权限的id组成的集合
//                                        $new_institutions=collect([$institution['institution_id']]);//数组转集合
//                                         
//                                        $old_in=$old_institutions->diff($new_institutions);   //原集合中存在，新集合中不存在的,要进行删除
//                                        //dd($old_permissions,$new_permissions,$old_in);
//                                        $new_in=$new_institutions->diff($old_institutions);   //新集合中存在，原集合中不存在的,要进行增加
//                                        //dd($old_institutions,$new_institutions,$old_in,$new_in);
//                                        if($old_in->isNotEmpty()){
//                                            $user->institutions()->detach($old_in);//删除管理机构
//                                        }
//                                        if($new_in->isNotEmpty()){
//                                            $user->institutions()->attach($new_in);//增加管理机构
//                                        }
//                                    //移除角色的所有权限...
//                                      //  $user->institutions()->detach();
//                                     //更新角色的权限...    
//					//$user->institutions()->attach($institution);
//				}
//			}
			flash_info($result,'前台用户修改成功','前台用户修改失败');
                        //Event::fire(new permChange());
                        //event(new \App\Events\userAction('\App\Models\User', intval($result->id),3,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}编辑前台用户".$result->name."{".$result->id."}"));
                        
			return redirect('/admin/fuser');
		} catch (Exception $e) {
			flash($e->getMessage(), 'danger');
			return redirect('/admin/fuser');
		}
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        try{
//                      Event::fire(new permChange());
//          event(new \App\Events\userAction('\App\Models\User',intval($user->id),2,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}删除了前台用户".$user->name."{".$user->id."}"));
//          //$user->institutions()->detach();
//          //$user->permissions()->detach();
//          //$user->roles()->detach();
//          $result=$user->delete();
//          flash_info($result,'用户删除成功','用户删除失败');
//          return redirect()->back();
//        } catch (Exception $e) {
//			flash($e, 'danger');
//			return redirect('/admin/fuser');
//		}

       
         //$result=$user->delete();
        
         //Event::fire(new permChange());
         //event(new \App\Events\userAction('\App\Models\User',$role->id,2,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}删除角色".$role->name."{".$role->id."}"));
         //return redirect()->back();
                
                 try {
			$result = User::destroy($id);
                        //$result = Menu::delete($id);
                        //dd($result);
			//if ($result) {
			//	$this->sortMenuSetCache();
			//}
			flash_info($result,'删除成功','删除失败');
		} catch (Exception $e) {
                        //dd($e->getMessage());
			flash($e->getMessage(), 'danger');
		}
        //$this->service->destroy($id);
        return redirect()->route('fuser.index');
          
    }
    

 
        
        
        public function getInstitutionList()
	{
		// 判断数据是否缓存
		//if (cache()->has('institutionList')) {
		//	return cache()->get('institutionList');
		//}
		return $this->sortInstitutionSetCache();
	}
        

	
	/**
	 * 递归菜单数据
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $institutions [description]
	 * @param  integer    $pid   [description]
	 * @return [type]            [description]
	 */
	private function sortInstitution($institutions,$pid=0)
	{
		$arr = [];
		if (empty($institutions)) {
			return '';
		}
		foreach ($institutions as $key => $v) {
                        //if($v['id'] == $pid){
                        //    $arr[$key] = $v;
                        //    $arr[$key]['child']= self::sortInstitution($institutions,$v['id']);
                        //}
			if ($v['parent_id'] == $pid) {
				$arr[$key] = $v;
				$arr[$key]['child'] = self::sortInstitution($institutions,$v['id']);
			}
		}
		return $arr;
	}
	
	/**
	 * 排序子菜单并缓存
	 * @author 晚黎
	 * @date   2017-11-06
	 * @return [type]     [description]
	 */
	private function sortInstitutionSetCache()
	{
		$institutions = \App\Models\Institution::all()->toArray();
		if ($institutions) {
                        //$pid=getCurrentPermission(getUser('admin'))['pid'];
                        //$curr_institution= \App\Models\Institution::find($pid)->toArray();
		$institutionList = $this->sortInstitution($institutions);
                        //$arr=[];
                        
                        //$arr[0]['child'] = $institutionList;
                        //dd($arr);
                        //$curr_institution['child'] = $institutionList; 
                        //$arr[] = $curr_institution;
                        //dump($institutionList,$curr_institution);
                        //$institutionList= $arr; 
                         //dd($institutionList,$curr_institution);
                        //unset($curr_institution,$pid,$arr);
			foreach ($institutionList as $key => &$v) {
				if ($v['child']) {
					$sort = array_column($v['child'], 'id');
					array_multisort($sort,SORT_DESC,$v['child']);
				}
			}
                        //dd($institutionList,$curr_institution);
			// 缓存菜单数据
			//cache()->forever('institutionList',$institutionList);
			return $institutionList;
			
		}
		return '';
	}
        
        public function  getCurrInstitutions()
        {
            
            //$g_institutions = $this->getInstitutionList();//得到当前用户所有可操作的机构
        
            //$pid=getCurrentPermission(getUser('admin'))['pid'];

            $institutionId=getCurrentInstitutionId();//获取当前用户的管理机构id
            $institutions = getInstitutionList($institutionId);
            //dump($institutions);
            $curr_institutions=_getAllInstitutionIdNameLevel($institutions);//取所有的id=>name组成的一级数组
            //dd($curr_institutions);
        //$curr_institution= \App\Models\Institution::find($pid)->toArray();
                        //dd($pid,$curr_institution);
  //           $in=_reSort($g_institutions,$pid);//返回一维数组格式数据
        //dump($in);
  //           $id_name = array_pluck($in, 'name', 'id'); //转换成以id值为键,name为值的一维数组
                        //dd($array);
                        //$in=_reSort($institutions->toArray(),$pid);
              //$g_in_s=  $pid.','._subchildren($in,$pid);//"2,8,9"
         

  //           $g_in=explode(',', $pid.','._subchildren($in,$pid));//保存所有的机构id数组保存
             //返回 机构号-》机构名 式的数组
       //      $curr_institutions = array_where($id_name, function ($value, $key) use ($g_in){
       //           if(in_array($key,$g_in)) return $value;
       //      });
            //dd($curr_institutions);
            return $curr_institutions;
            //return response()->json($curr_institutions);
           // return $this->message($curr_institutions);
            
        }
        
        public function getEmployees(Request $request)
        {
            //$employees =$request['institution_id']->employees->toarray();
            $employees=Institution::find($request['id'])->employees;
            //$employees = collect_pluck($employees, 'name','id');
            //echo response()->json($employees);
            return $this->success($employees);//ApiResponse在此接口中
            //return response()->json([
            //        'status' => 1,
            //        'code'   =>200,
            //        'msg'    => $employees,
            //    ]);
        }
}
