<?php

namespace App\Http\Controllers\Admin;
//use App\Services\PermissionService;
use App\Events\permChange;
use Illuminate\Http\Request;

use App\Http\Requests\PermissionCreateRequest;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Cache,Event;

class PermissionController extends Controller
{
    /*
    protected $fields = [
        'name'        => '',
        'label'       => '',
        'description' => '',
        'cid'         => 0,
        'icon'        => '',
    ];*/

    
    /*protected $service;
    public function __construct(PermissionService $service)
    {
        //parent::__construct();
        //$this->service = $service;
    }*/
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd($request);
        //$cid = (int)$cid;
        if ($request->ajax()) {

            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $order = $request->get('order');
            $columns = $request->get('columns');
            $search = $request->get('search');
            if(!isset($request['jgh'])){
             $cid = 0; //全部  
            }else{
             $cid = $request->get('jgh');
            
            }
            
            
            //if(intval($cid)==0){
                $data['recordsTotal'] = Permission::all()->count();
                if(strlen($search['value']) > 0) {
                    $data['recordsFiltered'] = Permission::where('name', 'LIKE', '%' . $search['value'] . '%')->orWhere('description', 'like', '%' . $search['value'] . '%')->orWhere('slug', 'like', '%' . $search['value'] . '%')->count();
                    if(intval($length)==-1){
                    $data['data'] = Permission::where('name', 'LIKE', '%' . $search['value'] . '%')->orWhere('description', 'like', '%' . $search['value'] . '%')->orWhere('slug', 'like', '%' . $search['value'] . '%')->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get();                       
                    }else{
                    $data['data'] = Permission::where('name', 'LIKE', '%' . $search['value'] . '%')->orWhere('description', 'like', '%' . $search['value'] . '%')->orWhere('slug', 'like', '%' . $search['value'] . '%')->skip($start)->take($length)->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get();
                    }
                }else{
                    $data['recordsFiltered']= $data['recordsTotal'];
                    if(intval($length)==-1){
                     $data['data'] = Permission::orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get();
 
                    }else{
                      $data['data'] = Permission::skip($start)->take($length)->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get();
    
                    }

                }
            //}else{
            //    $data['recordsTotal'] = Permission::where('cid', $cid)->count();
            //    if(strlen($search['value']) > 0) {
            //        $data['recordsFiltered'] = Permission::where('cid', $cid)->where('name', 'LIKE', '%' . $search['value'] . '%')->orWhere('description', 'like', '%' . $search['value'] . '%')->orWhere('slug', 'like', '%' . $search['value'] . '%')->count();
            //        if(intval($length)==-1){
            //            $data['data'] = Permission::where('cid', $cid)->where('name', 'LIKE', '%' . $search['value'] . '%')->orWhere('description', 'like', '%' . $search['value'] . '%')->orWhere('slug', 'like', '%' . $search['value'] . '%')->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get();

            //        }else{
            //            $data['data'] = Permission::where('cid', $cid)->where('name', 'LIKE', '%' . $search['value'] . '%')->orWhere('description', 'like', '%' . $search['value'] . '%')->orWhere('slug', 'like', '%' . $search['value'] . '%')->skip($start)->take($length)->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get();
            //        }
            //    }else{
            //            $data['recordsFiltered']= $data['recordsTotal'];
            //            if(intval($length)==-1){
            //                $data['data'] = Permission::where('cid', $cid)->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get(); 
                
            //            }else{
            //                $data['data'] = Permission::where('cid', $cid)->skip($start)->take($length)->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get(); 
                
            //            }
            //        }
            //}
           
               //$data['recordsTotal'] = Permission::all()->count();
          //  if (strlen($search['value']) > 0) {
                //$data['recordsFiltered'] = Permission::where('cid', $cid)->where(function ($query) use ($search) {
          //      $data['recordsFiltered'] = Permission::where('name', 'LIKE', '%' . $search['value'] . '%')
          //              ->orWhere('description', 'like', '%' . $search['value'] . '%')
          //              ->orWhere('label', 'like', '%' . $search['value'] . '%')
          //      ->count();
                //$data['data'] = Permission::where('cid', $cid)->where(function ($query) use ($search) {
           //     $data['data'] = Permission::where('name', 'LIKE', '%' . $search['value'] . '%')
           //             ->orWhere('description', 'like', '%' . $search['value'] . '%')
           //             ->orWhere('label', 'like', '%' . $search['value'] . '%')
                
           //         ->skip($start)->take($length)
           //         ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
           //         ->get();
           // } else {
                //$data['recordsFiltered'] = Permission::where('cid', $cid)->count();
                //$data['recordsFiltered'] = Permission::all()->count();
               
          //      $data['data'] = Permission::skip($start)->take($length)
           //         ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
            //        ->get();
            //}
            //foreach($data['data'] as $k=>$v){
            //      $data['data'][$k]['row_no']= $k+1;
            //}
            $data['data'] = setRow_No($data['data']);
            return response()->json($data);
        }
        //$datas['cid'] = $cid;
        //if ($cid > 0) {
           // $datas['data'] = Permission::find($cid);
            //$data['recordsTotal'] = $data['recordsFiltered']=Permission::all()->count();
            //$data['data'] = Permission::all();
            //$data['data'] = setRow_No($data['data']);
            //foreach($datas['data'] as $k=>$v){
            //      $datas['data'][$k]['row_no']= $k+1;
            //}
        //}
        return view('admin.permission.index');

        
        //$result = $this->service->index();
        
       // return request()->ajax() ? $result : view('admin.permission.index')->with($result);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*
        $data = [];
        foreach ($this->fields as $field => $default) {
            $data[$field] = old($field, $default);
        }
        $data['cid'] = $cid;
        return view('admin.permission.create', $data);
         * 
         */
        return view('admin.permission.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param PremissionCreateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionCreateRequest $request)
    {
        
        //$permission = new Permission();
        //$permission->name = $request['name'];
        //$permission->label = $request['label'];
        //$permission->description = $request['description'];
        
        //$permission->cid = $request['cid'];
        //if(intval($request['cid'])<0){
        //   $permission->icon=""; 
        //}else{
        //   $permission->icon = $request['icon'];
        //}
        
        //foreach (array_keys($this->fields) as $field) {
        //    $permission->$field = $request->get($field, $this->fields[$field]);
        //}
        $permission = Permission::create(request(['name','slug','description']));
        if($permission ){

            setUserPermissions(getUser('admin')); //缓存当前用户权限
            SetallPermissionCache();//缓存所有权限
        }
       
            //Event::fire(new permChange());
            //event(new \App\Events\userAction('\App\Models\Permission', $permission->id, 1, '添加了权限:' . $permission->name . '(' . $permission->slug . ')'));
            flash_info($permission,'权限增加成功','权限增加权限增加失败');
            
           // Cache::forget('menus'); //清空菜单缓存
            //缓存菜单
            //Cache::store('file')->rememberForever('menus', function () {
            //return \App\Models\Permission::where('name', 'LIKE', '%index')
            //    ->orWhere('cid', 1) //一级菜单
            //    ->get();
            //});
            //return redirect('/admin/permission/')->withSuccess('添加成功！');
            return redirect('/admin/permission/');
            //return redirect()->back();
            
        

        //$route = $this->service->store($request->all());
        //return redirect()->route($route);
    }
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        
        //$permission = Permission::find($id);
        //if (!$permission){
        //    flash("找不到该权限!",'danger')->important();
        //    return redirect('/admin/permission');
            
        //}
        //$data = ['id' => $permission->id];
        //foreach (array_keys($this->fields) as $field) {
        //    $data[$field] = old($field, $permission->$field);
        //}
        //dd($permission->label);
        
        return view('admin.permission.edit', $permission);
        //return view('admin.permission.edit',$permission);

        //$result = $this->service->edit($id);
        //return view('admin.permission.edit')->with($result);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param PermissionUpdateRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionCreateRequest $request, Permission $permission)
    {
       
        //$permission = Permission::find((int)$id);
        //foreach (array_keys($this->fields) as $field) {
        //    $permission->$field = $request->get($field, $this->fields[$field]);
        //}
        $result=$permission->update(request(['name','slug','description']));
        if($result){
             //缓存所有权限
         SetallPermissionCache();
         setUserPermissions(getUser('admin'));
        }
        
                               
                                
        
        flash_info($result,'修改权限成功','修改权限失败');
            
        //    Cache::forget('menus'); //清空菜单缓存
            //缓存菜单
        //    Cache::store('file')->rememberForever('menus', function () {
        //    return \App\Models\Permission::where('name', 'LIKE', '%index')
        //        ->orWhere('cid', 1) //一级菜单
        //        ->get();
        //    });
        //Event::fire(new permChange());
        //event(new \App\Events\userAction('\App\Models\Permission', $permission->id, 3, '修改了权限:' . $permission->name . '(' . $permission->slug . ')'));
        //return redirect('admin/permission/' . $permission->cid)->withSuccess('修改成功！');

        //$route = $this->service->update($request->all(), $id);
        return redirect('/admin/permission/');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        /**
        $child = Permission::where('cid', $id)->first();
        if ($child) {
            return redirect()->back()
                ->withErrors("请先将该权限的子权限删除后再做删除操作!");
        }
        $tag = Permission::find((int)$id);
        foreach ($tag->roles as $v) {
            $tag->roles()->detach($v->id);
        }
        if ($tag) {
            $tag->delete();
        } else {
            return redirect()->back()
                ->withErrors("删除失败");
        }

        Event::fire(new permChange());
        event(new \App\Events\userAction('\App\Models\Permission', $tag->id, 2, '删除了权限:' . $tag->name . '(' . $tag->label . ')'));
        return redirect()->back()
            ->withSuccess("删除成功");
         * 
         */
        //$this->service->destroy($id);
        //Event::fire(new permChange());
        //event(new \App\Events\userAction('\App\Models\Permission', $permission->id, 2, '删除了权限:' . $permission->name . '(' . $permission->slug . ')'));

        $result = $permission->delete();
        setUserPermissions(getUser('admin'));                         
        SetallPermissionCache();
        flash_info($result,'权限删除成功','权限删除失败');
        return redirect()->route('permission.index');
    }

}
