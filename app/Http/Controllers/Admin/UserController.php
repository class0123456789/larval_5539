<?php

namespace App\Http\Controllers\Admin;

use App\Events\permChange;
use App\Models\Role;
use App\Models\Permission;
use App\Models\AdminUser as User;
use Illuminate\Http\Request;
use App\Http\Requests\AdminUserCreateRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Cache,Event;

class UserController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $currinstitutions=$this->getCurrInstitutions();
        $arr = _getAllInstitutionId($currinstitutions);
        $g_in_s = implode(',',$arr);
        $mysql = "SELECT @row_no:=@row_no+1 as row_no, a.id ,a.name,a.email ,CONCAT('[',a.institution_id ,']',b.name) as institution_name  FROM admin_users a ,(select  @row_no:=0) t  ,admin_institutions b where a.institution_id=b.id and b.id in(%s) ";
        $countmysql = "select count(id) as total from  (%s) z";
        //dd(\Route::getRoutes()->getRoutesByMethod()['GET']['admin/menu'],haspermission('menu/index')); //测试路由
//        if($request->ajax()) {
//            $data = array();
//            $data['draw'] = $request->get('draw');
//            $start = $request->get('start');
//            $length = $request->get('length');
//            $order = $request->get('order');
//            $columns = $request->get('columns');
//            $search = $request->get('search');
//            $data['recordsTotal'] = User::count();
//            if (strlen($search['value']) > 0) {
//                $data['recordsFiltered'] = User::where(function ($query) use ($search) {
//                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')
//                        ->orWhere('email', 'like', '%' . $search['value'] . '%');
//                })->count();
//                $data['data'] = User::where(function ($query) use ($search) {
//                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')
//                        ->orWhere('email', 'like', '%' . $search['value'] . '%');
//                })
//                    ->skip($start)->take($length)
//                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
//                    ->get();
//            } else {
//                $data['recordsFiltered'] = User::count();
//                $data['data'] = User::skip($start)->take($length)
//                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
//                    ->get();
//            }
//             $data['data'] = setRow_No($data['data']);
//            return response()->json($data);
//        }
        if ($request->ajax()) {
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
                $where = " and ((a.name like '%".$search['value']."%' ) or (a.email  like '%".$search['value']."%')  or (b.name  like '%".$search['value']."%') ) ";
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
//         $data['recordsTotal'] = $data['recordsFiltered']=User::all()->count();
//            $data['data'] = User::all();
//            $data['data'] = setRow_No($data['data']);

        return view('admin.user.index',compact('currinstitutions'));
        //return view('admin.user.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {           
                $institutionId=getCurrentInstitutionId();//获取当前用户的管理机构id
                $institutions = getInstitutionList($institutionId);
                //1代表获取所有机构,默认参数就是1
                //因为是建立用户此处就不用管理机构来限制了
               // $institutions = getInstitutionList(1);
                //$permissions = getAllPermissions();
                //$permissions = $this->getAllPermissions();
                 $permissions=getAllPermissions();
                
                //dd($permissions);
		        $roles = $this->getAllRole();


                return view('admin.user.create', compact(['permissions','roles','institutions']));
        
        
        

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminUserCreateRequest $request)
    {
        $request['password']=bcrypt($request['password']);
        //$password = bcrypt($request->get('password'));
        $user = User::create($request->all());
         //dd($user->institutions());

        //$user->save();
        if (isset($request['roles']) && is_array($request['roles']) ) {
            //$user->roles()->detach();
            $user->roles()->attach($request['roles']);
        }
                //$user->save();
        if (isset($request['permission']) && is_array($request['permission']) ) {
            $user->permissions()->attach($request['permission']);
        }
       // if (isset($request['institution_id']) ) {
            //$user->institutions()->attach($request['institution_id']);
       //     $user->institutions()->attach($request['institution_id']);
       // }
        flash_info($user,'用户增加成功','用户增加失败');
        //Event::fire(new permChange());
        //event(new \App\Events\userAction('\App\Models\AdminUser', $user->id, 1, '添加了用户' . $user->name));
        return redirect('/admin/user');
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
			$user = User::with('permissions','roles','institution')->find($id);
                        //dd($user->institutions()->first());
                        //$institution = \App\Models\Institution::find(getCurrentPermission($user)['pid']);
                        //dd($institutions);
			
                         //return view('admin.user.show',compact('roles','permissions','institutions','user'));
                        return view('admin.user.show',compact('user'));
		} catch (Exception $e) {
			flash('查看错误', 'danger');
			redirect('/admin/user');
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
                        //with预加载
			$user = User::with('permissions','Roles','institution')->find($id);
           $institutionId=getCurrentInstitutionId();//获取当前用户的管理机构id
           $institutions = getInstitutionList($institutionId);
                        //$institutions = getInstitutionList(1);//不限制使用这个
                        //dd($user->institutions()->first()->id);
                        //dd($institution);
                        //dd($role->permissions);
			//$permissions = $this->getAllPermissions();
           $permissions=getAllPermissions();
                        $roles = $this->getAllRole();
			//return compact('role', 'permissions');
                        return view('admin.user.edit',compact('user', 'permissions','roles','institution','institutions'));
	} catch (Exception $e) {
			flash('没有找到用户'.$id."记录", 'danger');
			return redirect('/admin/user');
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
    public function update(AdminUserCreateRequest $request,USER $user)
    {

             //dd($request);
        
        
                try { 
                      //$id=$request->route('user');
                        if(isset($request['password']) && is_string($request['password']) && (strlen($request['password'])>0)){
                            $request['password']=bcrypt($request['password']);
                        }else{
                           unset($request['password']) ;
                        }


                        //$result=User::where('id',$id)->update($request->all());
                        //dd($result);
                        
			$result=$user->update($request->all());
                        //dd($result,$request['permission']);
                        $permissions=$request->all(['permission']);
                        //$institution=$request->all(['institution_id']);
                        //dd($institution);
                        $roles=$request->all(['roles']);
                        //dd($permissions);
			if ($result) {
				// 更新角色权限关系
				if($permissions) {
                                        $old_permissions=$user->permissions->pluck('id');    //取所有权限的id组成的集合
                                        $new_permissions=collect($permissions['permission']);//数组转集合
                                         
                                        $old_in=$old_permissions->diff($new_permissions);   //原集合中存在，新集合中不存在的,要进行删除
                                        //dd($old_permissions,$new_permissions,$old_in);
                                        $new_in=$new_permissions->diff($old_permissions);   //新集合中存在，原集合中不存在的,要进行增加
                                        //dump($old_permissions,$new_permissions,$old_in,$new_in);
                                        if($old_in->isNotEmpty()){
                                            $user->permissions()->detach($old_in);//删除权限
                                        }
                                        if($new_in->isNotEmpty()){
                                            $user->permissions()->attach($new_in);//增加权限
                                        }
                                    
                                    
                                    //移除角色的所有权限...
                                        //$user->permissions()->detach();
                                     //更新角色的权限...    
					//$user->permissions()->attach($permissions['permission']);
				}else{
					//移除角色的所有权限...
                                        $user->permissions()->detach();
				}
                                
                                if($roles) {
                                        $old_roles=$user->roles->pluck('id');    //取所有权限的id组成的集合
                                        $new_roles=collect($roles['roles']);//数组转集合
                                         
                                        $old_in=$old_roles->diff($new_roles);   //原集合中存在，新集合中不存在的,要进行删除
                                        //dd($old_permissions,$new_permissions,$old_in);
                                        $new_in=$new_roles->diff($old_roles);   //新集合中存在，原集合中不存在的,要进行增加
                                        //dd($old_permissions,$new_permissions,$old_in,$new_in);
                                        if($old_in->isNotEmpty()){
                                            $user->roles()->detach($old_in);//删除角色
                                        }
                                        if($new_in->isNotEmpty()){
                                            $user->roles()->attach($new_in);//增加角色
                                        }
                                    
                                    //移除角色的所有权限...
                                       // $user->roles()->detach();
                                     //更新角色的权限...    
					//$user->roles()->attach($roles['roles']);
				}else{
					//移除角色的所有权限...
                                        $user->roles()->detach();
				}
				//cacheClear();
                                //if($institution) {//更新管理机构
                                //        $old_institutions=$user->institutions->pluck('id');    //取所有权限的id组成的集合
                                //        $new_institutions=collect([$institution['institution_id']]);//数组转集合
                                         
                                //        $old_in=$old_institutions->diff($new_institutions);   //原集合中存在，新集合中不存在的,要进行删除
                                        //dd($old_permissions,$new_permissions,$old_in);
                                //        $new_in=$new_institutions->diff($old_institutions);   //新集合中存在，原集合中不存在的,要进行增加
                                        //dd($old_institutions,$new_institutions,$old_in,$new_in);
                                 //       if($old_in->isNotEmpty()){
                                 //           $user->institutions()->detach($old_in);//删除管理机构
                                 //       }
                                 //       if($new_in->isNotEmpty()){
                                 //           $user->institutions()->attach($new_in);//增加管理机构
                                 //       }
                                    //移除角色的所有权限...
                                      //  $user->institutions()->detach();
                                     //更新角色的权限...    
					//$user->institutions()->attach($institution);
				//}
                                if(getUserId('admin') == $user->id){
                                    //缓存用户权限
                                    setUserPermissions($user);
                                }
			}
			flash_info($result,'用户修改成功','用户修改失败');
                       // Event::fire(new permChange());
                       // event(new \App\Events\userAction('\App\Models\User',$user->id,3,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}编辑用户".$user->name."{".$user->id."}"));
                        //return redirect('/admin/role')->withSuccess('修改成功！');
			return redirect('/admin/user');
		} catch (Exception $e) {
			flash($e->getMessage(), 'danger');
			return redirect('/admin/user');
		}
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if($user->id===1){
            flash('当前是超级管理员不能删除', 'danger');
            return redirect()->back();
        }
       if($user->id===auth('admin')->user()->id){
            flash('不能删除自已', 'danger');
            return redirect()->back();
        }
        //Event::fire(new permChange());
         //event(new \App\Events\userAction('\App\Models\User',$user->id,2,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}删除用户".$user->name."{".$user->id."}"));
          //$user->institutions()->detach();
          $user->permissions()->detach();
          $user->roles()->detach();
          $result=$user->delete();
          flash_info($result,'用户删除成功','用户删除失败');
          return redirect()->back();
       
         //$result=$user->delete();
        
         //Event::fire(new permChange());
         //event(new \App\Events\userAction('\App\Models\User',$role->id,2,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}删除角色".$role->name."{".$role->id."}"));
         //return redirect()->back();
          
    }
    
    /**
	 * 获取所有权限
	 * @author 晚黎
	 * @date   2017-11-06
	 * @return [type]     [description]
	 */
	public function getAllPermissions()
	{
                 $array = [];
		 //$permissions = Permission::all(['id', 'name', 'slug','description'])->toArray();
                 $permissions=getAllPermissions();
                 //dd($permissions);
		 //if ($permissions->isNotEmpty()) {
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
                return $array;
	}
        
    private function getAllRole()
	{
		return Role::all(['id', 'name']);
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
        
        
}
