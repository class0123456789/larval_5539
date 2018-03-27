<?php

namespace App\Http\Controllers\Admin;
use App\Events\permChange;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\RoleCreateRequest;

use App\Http\Controllers\Controller;
use App\Models\Role;

use log;
use Auth;
use Cache,Event;
use Exception;

class RoleController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd($request->ajax());
        if ($request->ajax()) {
            //dump('is ajax');
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $order = $request->get('order');
            $columns = $request->get('columns');
            $search = $request->get('search');
                $data['recordsTotal'] = Role::all()->count();
                if(strlen($search['value']) > 0) {
                    $data['recordsFiltered'] = Role::where('name', 'LIKE', '%' . $search['value'] . '%')->orWhere('description', 'like', '%' . $search['value'] . '%')->orWhere('slug', 'like', '%' . $search['value'] . '%')->count();
                    if(intval($length)==-1){
                    $data['data'] = Role::where('name', 'LIKE', '%' . $search['value'] . '%')->orWhere('description', 'like', '%' . $search['value'] . '%')->orWhere('slug', 'like', '%' . $search['value'] . '%')->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get();                       
                    }else{
                    $data['data'] = Role::where('name', 'LIKE', '%' . $search['value'] . '%')->orWhere('description', 'like', '%' . $search['value'] . '%')->orWhere('slug', 'like', '%' . $search['value'] . '%')->skip($start)->take($length)->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get();
                    }
                }else{
                    $data['recordsFiltered']= $data['recordsTotal'];
                    if(intval($length)==-1){
                     $data['data'] = Role::orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get();
 
                    }else{
                      $data['data'] = Role::skip($start)->take($length)->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get();
    
                    }

                }
            //dd(response()->json($data));
                $data['data'] = setRow_No($data['data']);
            return response()->json($data);
        }
         
         //$data['recordsTotal'] = $data['recordsFiltered']=Role::all()->count();
         //   $data['data'] = Role::all();
         //   $data['data'] = setRow_No($data['data']);
            //dd($data);
        //return view('admin.role.index',['data'=>$data]);
        return view('admin.role.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //$permissions=$this->getAllPermissions();
        $permissions=getAllPermissions();
        //return $array;
       
        //dd($permissions);

        return view('admin.role.create', ['permissions'=>$permissions]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param RoleCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleCreateRequest $request)
    {
        // dd($request->get('permission'));
        //$role = new Role();
        //dd($request->all());
       try {
			$role = Role::create($request->all());
			if ($role  && isset($request['permission']) && $request['permission']) {
				//更新角色权限关系
                            $permission_ids=$request['permission'];
                            //$role->permissions()->attach($permission_ids);//批量增加中间表数据 的权限
                            $role->grantPermission($permission_ids);
                            
                          //  foreach ($permission_ids as $permission_id){
                                
                               //$permission= Permission::find($permission_id);
                               //dd($permission);create([   'message' => '一条新的评论。',]);
                               //dd($role->permissions()->attach(['permission_id'=>$permission_id]));
                          //     $role->permissions()->attach($permission_id);//增加中间表数据 的权限
                                //dd($permission_id);
                          //  }
                setUserPermissions(getUser('admin'));
            }
			 flash_info($role,'角色增加成功','角色增加失败');
                         //Event::fire(new permChange());
                         //event(new \App\Events\userAction('\App\Models\Role',$role->id,1,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}添加角色".$role->name."{".$role->id."}"));
                         return redirect('/admin/role');
			//return isset($attributes['rediret']) ? $this->createRoute : $this->indexRoute;
		} catch (Exception $e) {
			//return $this->createRoute;
                    
                     flash($e->getMessage(), 'danger');
                    return redirect('/admin/role');
            }
        // dd($request->get('permission'));
        //Event::fire(new permChange());
        //event(new \App\Events\userAction('\App\Models\Role',$role->id,1,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}添加角色".$role->name."{".$role->id."}"));
       // return redirect('/admin/role');
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
			$role = Role::with('permissions')->find($id);
			
                         return view('admin.role.show',compact('role'));
		} catch (Exception $e) {
			//flash('查看错误', 'danger');
                         flash($e->getMessage(), 'danger');
                        redirect('/admin/role');
			//redirect('/admin/role');
		}
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($roleId)
    {

        
       try {
                        //with预加载
			$role = Role::with('permissions')->find($roleId);
                        //dd($role->permissions);
			//$permissions = $this->getAllPermissions();
           $permissions=getAllPermissions();
			//return compact('role', 'permissions');
                        return view('admin.role.edit',compact('role', 'permissions'));
	} catch (Exception $e) {
			//flash('没有找到'.$roleId."记录", 'danger');
                         flash($e->getMessage(), 'danger');
			return redirect('/admin/role');
	}
        
    }
    /**
     * Update the specified resource in storage.
     *
     * @param PermissionUpdateRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleCreateRequest $request, Role $role)
    {

       // dd($request->all(['name','slug','description']));
         
        
        		try {
			$result=$role->update($request->all(['name','description']));
                        //dd($result,request['permission']);
                        $permissions=$request->all(['permission']);
                        //dd($permissions);
			if ($result) {
				// 更新角色权限关系
				if($permissions) {
                                        $old_permissions=$role->permissions->pluck('id');    //取所有权限的id组成的集合
                                        $new_permissions=collect($permissions['permission']);//数组转集合
                                         
                                        $old_in=$old_permissions->diff($new_permissions);   //原集合中存在，新集合中不存在的,要进行删除
                                        //dd($old_permissions,$new_permissions,$old_in);
                                        $new_in=$new_permissions->diff($old_permissions);   //新集合中存在，原集合中不存在的,要进行增加
                                        //dump($old_permissions,$new_permissions,$old_in,$new_in);
                                        if($old_in->isNotEmpty()){
                                            $role->permissions()->detach($old_in);//删除权限
                                        }
                                        if($new_in->isNotEmpty()){
                                            $role->permissions()->attach($new_in);//增加权限
                                        }
                                        
                                    //移除角色的所有权限...
                                       // $role->permissions()->detach();
                                     //更新角色的权限...    
					//$role->permissions()->attach($permissions['permission']);
				}else{
					//移除角色的所有权限...
                                        $role->permissions()->detach();
				}
                setUserPermissions(getUser('admin'));
                                 //$key = 'user_'.getUserId('admin');
                                 //cache()->forget($key);  
			}
			flash_info($result,'角色修改成功','角色修改失败');
                        //Event::fire(new permChange());
                        //event(new \App\Events\userAction('\App\Models\Role',$role->id,3,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}编辑角色".$role->name."{".$role->id."}"));
                        //return redirect('/admin/role')->withSuccess('修改成功！');
			return redirect('/admin/role');
		} catch (Exception $e) {
			//flash('角色修改出错', 'danger');
                         flash($e->getMessage(), 'danger');
			return redirect('/admin/role');
		}
        
        
        //$role->permissions()->sync($request->get('permissions',[]));
        
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //$role = Role::find((int)$id);
        //移除此角色的所有权限...
        $role->permissions()->detach();
       
        $result=$role->delete();
        flash_info($result,'角色删除成功','角色删除失败');
         Event::fire(new permChange());
         event(new \App\Events\userAction('\App\Models\Role',$role->id,2,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}删除角色".$role->name."{".$role->id."}"));
          //$key = 'user_'.getUserId('admin');
          //cache()->forget($key); 
         setUserPermissions(getUser('admin'));
         return redirect()->back();
          
    }
    
    
    	/**
	 * 获取所有权限
	 * @author 晚黎
	 * @date   2017-11-06
	 * @return [type]     [description]
	 */
	public function getAllPermissions()
	{

        //$permissions = Permission::all(['id', 'name', 'slug','description'])->toArray();
        $permissions=getAllPermissions();
        //dd($permissions);
        //if ($permissions->isNotEmpty()) {
//        $array = [];
//        if ($permissions) {
//            foreach ($permissions as $v) {
//                if(strpos($v['slug'],'.')){
//                    $temp = explode('.', $v['slug']); //点分隔的命名路由权限
//                }elseif (strpos($v['slug'],'/')) {
//                    $temp = explode('/', $v['slug']);//斜扛分隔的没有命名路由权限 或者跳转  这里一般是自定义的路由
//                }elseif (strpos($v['slug'],'::')) {//双冒号分隔的命名路由权限 或者跳转
//                    $temp = explode('::', $v['slug']);
//                }
//
//
//                $array[$temp[0]][] = $v;
//            }
//        }
//                return $array;
        return $permissions;
	}
}
