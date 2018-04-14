<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DeviceBrand;
use App\Http\Requests\BrandRequest;

class DeviceBrandController extends Controller
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
            $data['recordsTotal'] = DeviceBrand::all()->count();
            if(strlen($search['value']) > 0) {
                $data['recordsFiltered'] = DeviceBrand::where('name', 'LIKE', '%' . $search['value'] . '%')->orWhere('contact', 'like', '%' . $search['value'] . '%')->orWhere('address', 'like', '%' . $search['value'] . '%')->count();
                if(intval($length)==-1){
                    $data['data'] = DeviceBrand::where('name', 'LIKE', '%' . $search['value'] . '%')->orWhere('contact', 'like', '%' . $search['value'] . '%')->orWhere('address', 'like', '%' . $search['value'] . '%')->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get();
                }else{
                    $data['data'] = DeviceBrand::where('name', 'LIKE', '%' . $search['value'] . '%')->orWhere('contact', 'like', '%' . $search['value'] . '%')->orWhere('address', 'like', '%' . $search['value'] . '%')->skip($start)->take($length)->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get();
                }
            }else{
                $data['recordsFiltered']= $data['recordsTotal'];
                if(intval($length)==-1){
                    $data['data'] = DeviceBrand::orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get();

                }else{
                    $data['data'] = DeviceBrand::skip($start)->take($length)->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])->get();

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
        return view('admin.brand.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BrandRequest $request)
    {
        try {
            $brand = DeviceBrand::create($request->all());

            flash_info($brand,'品牌增加成功','品牌增加失败');
            //Event::fire(new permChange());
            //event(new \App\Events\userAction('\App\Models\Role',$role->id,1,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}添加角色".$role->name."{".$role->id."}"));
            return redirect('/admin/brand');
            //return isset($attributes['rediret']) ? $this->createRoute : $this->indexRoute;
        } catch (Exception $e) {
            //return $this->createRoute;

            flash($e->getMessage(), 'danger');
            return redirect('/admin/brand');
        }
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
            $brand = DeviceBrand::find($id);
            //dd($brand);
            //$deviceclasses=$this->getCurrDeviceClasses();
            //dd($deviceclasses);
            // dd( $devicemodel['brand']->name);
            return view('admin.brand.show',compact('brand'));
        } catch (Exception $e) {
            //flash('查看错误', 'danger');
            flash($e->getMessage(), 'danger');
            redirect('/admin/brand');
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
            $brand = DeviceBrand::find($id);
            //dd($brand);
            //dd($role->permissions);
            //$permissions = $this->getAllPermissions();
            //$permissions=getAllPermissions();
            //return compact('role', 'permissions');
            return view('admin.brand.edit',compact('brand'));
        } catch (Exception $e) {
            //flash('没有找到'.$roleId."记录", 'danger');
            flash($e->getMessage(), 'danger');
            return redirect('/admin/brand');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BrandRequest $request, DeviceBrand $brand)
    {

        try {
            $result=$brand->update($request->all(['name','contact','phone','address']));
            //dd($result,request['permission']);
            //$permissions=$request->all(['permission']);
            //dd($permissions);

                //setUserPermissions(getUser('admin'));
                //$key = 'user_'.getUserId('admin');
                //cache()->forget($key);

            flash_info($result,'品牌修改成功','品牌修改失败');
            //Event::fire(new permChange());
            //event(new \App\Events\userAction('\App\Models\Role',$role->id,3,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}编辑角色".$role->name."{".$role->id."}"));
            //return redirect('/admin/role')->withSuccess('修改成功！');
            return redirect('/admin/brand');
        } catch (Exception $e) {
            //flash('角色修改出错', 'danger');
            flash($e->getMessage(), 'danger');
            return redirect('/admin/brand');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeviceBrand $brand)
    {
        //$role = Role::find((int)$id);
        //移除此角色的所有权限...
        //$role->permissions()->detach();

        $result=$brand->delete();
        flash_info($result,'品牌删除成功','品牌删除失败');
        //Event::fire(new permChange());
        //event(new \App\Events\userAction('\App\Models\Role',$role->id,2,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}删除角色".$role->name."{".$role->id."}"));
        //$key = 'user_'.getUserId('admin');
        //cache()->forget($key);
        //setUserPermissions(getUser('admin'));
        return redirect()->back();
    }
}
