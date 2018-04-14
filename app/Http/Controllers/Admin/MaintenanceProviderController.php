<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MaintenanceProviderRequest;
use App\Models\MaintenanceProvider;

class MaintenanceProviderController extends Controller
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
            $data['recordsTotal'] = MaintenanceProvider::all()->count();
            if(strlen($search['value']) > 0) {
                $data['recordsFiltered'] = MaintenanceProvider::where('name', 'LIKE', '%' . $search['value'] . '%')->orWhere('contact', 'like', '%' . $search['value'] . '%')->orWhere('address', 'like', '%' . $search['value'] . '%')->count();
                if(intval($length)==-1){
                    $data['data'] = MaintenanceProvider::where('name', 'LIKE', '%' . $search['value'] . '%')->orWhere('contact', 'like', '%' . $search['value'] . '%')->orWhere('address', 'like', '%' . $search['value'] . '%')->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get();
                }else{
                    $data['data'] = MaintenanceProvider::where('name', 'LIKE', '%' . $search['value'] . '%')->orWhere('contact', 'like', '%' . $search['value'] . '%')->orWhere('address', 'like', '%' . $search['value'] . '%')->skip($start)->take($length)->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get();
                }
            }else{
                $data['recordsFiltered']= $data['recordsTotal'];
                if(intval($length)==-1){
                    $data['data'] = MaintenanceProvider::orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get();

                }else{
                    $data['data'] = MaintenanceProvider::skip($start)->take($length)->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get();

                }

            }
            //dd(response()->json($data));
            $data['data'] = setRow_No($data['data']);
            return response()->json($data);
        }

        //$data['recordsTotal'] = $data['recordsFiltered']=Role::all()->count();
        //$data['data'] = Role::all();
        //$data['data'] = setRow_No($data['data']);
        //dd($data);
        return view('admin.maintenanceprovider.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenanceprovider.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MaintenanceProviderRequest $request)
    {
        try {
            $maintenanceprovider = MaintenanceProvider::create($request->all());

            flash_info($maintenanceprovider,'厂商增加成功','厂商增加失败');
            //Event::fire(new permChange());
            //event(new \App\Events\userAction('\App\Models\Role',$role->id,1,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}添加角色".$role->name."{".$role->id."}"));

            //return isset($attributes['rediret']) ? $this->createRoute : $this->indexRoute;
        } catch (Exception $e) {
            //return $this->createRoute;

            flash($e->getMessage(), 'danger');

        }
        return redirect('/admin/maintenanceprovider');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $maintenanceprovider = MaintenanceProvider::find($id);
            //dd($maintenanceprovider);
            //$deviceclasses=$this->getCurrDeviceClasses();
            //dd($deviceclasses);
            // dd( $devicemodel['brand']->name);
            return view('admin.maintenanceprovider.show',compact('maintenanceprovider'));
        } catch (Exception $e) {
            //flash('查看错误', 'danger');
            flash($e->getMessage(), 'danger');
            redirect('/admin/maintenanceprovider');
            //redirect('/admin/role');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            //with预加载
            $maintenanceprovider = MaintenanceProvider::find($id);
            //dd($role->permissions);
            //$permissions = $this->getAllPermissions();
            //$permissions=getAllPermissions();
            //return compact('role', 'permissions');
            return view('admin.maintenanceprovider.edit',compact('maintenanceprovider'));
        } catch (Exception $e) {
            //flash('没有找到'.$roleId."记录", 'danger');
            flash($e->getMessage(), 'danger');
            return redirect('/admin/maintenanceprovider');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MaintenanceProviderRequest $request, MaintenanceProvider $maintenanceprovider)
    {

        try {
            $result=$maintenanceprovider->update($request->all(['name','contact','phone','address']));
            //dd($result,request['permission']);
            //$permissions=$request->all(['permission']);
            //dd($permissions);

            //setUserPermissions(getUser('admin'));
            //$key = 'user_'.getUserId('admin');
            //cache()->forget($key);

            flash_info($result,'厂商修改成功','厂商修改失败');
            //Event::fire(new permChange());
            //event(new \App\Events\userAction('\App\Models\Role',$role->id,3,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}编辑角色".$role->name."{".$role->id."}"));
            //return redirect('/admin/role')->withSuccess('修改成功！');
            return redirect('/admin/maintenanceprovider');
        } catch (Exception $e) {
            //flash('角色修改出错', 'danger');
            flash($e->getMessage(), 'danger');
            return redirect('/admin/maintenanceprovider');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MaintenanceProvider $maintenanceprovider)
    {
        //$role = Role::find((int)$id);
        //移除此角色的所有权限...
        //$role->permissions()->detach();

        $result=$maintenanceprovider->delete();
        flash_info($result,'厂商删除成功','厂商删除失败');
        //Event::fire(new permChange());
        //event(new \App\Events\userAction('\App\Models\Role',$role->id,2,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}删除角色".$role->name."{".$role->id."}"));
        //$key = 'user_'.getUserId('admin');
        //cache()->forget($key);
        //setUserPermissions(getUser('admin'));
        return redirect()->back();
    }
}
