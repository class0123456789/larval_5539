<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DeviceSaveState;
use App\Http\Requests\DeviceSaveStateRequest;
class DeviceSaveStateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd($request);
        if ($request->ajax()) {
            //dd('is ajax');
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $order = $request->get('order');
            $columns = $request->get('columns');
            $search = $request->get('search');
            $data['recordsTotal'] = DeviceSaveState::count();
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = DeviceSaveState::where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%');
                    //->orWhere('description', 'like', '%' . $search['value'] . '%');
                })->count();
                $data['data'] = DeviceSaveState::where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%');
                    //->orWhere('description', 'like', '%' . $search['value'] . '%');
                })
                    ->skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            } else {
                $data['recordsFiltered'] = DeviceSaveState::count();
                $data['data'] = DeviceSaveState::skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            }
            //dd(response()->json($data));
            $data['data'] = setRow_No($data['data']);
            return response()->json($data);
        }
        //$data['recordsTotal'] = $data['recordsFiltered']=Kind::all()->count();
        //$data['data'] = Kind::all();
        //$data['data'] = setRow_No($data['data']);
        return view('admin.devicesavestate.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.devicesavestate.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeviceSaveStateRequest $request)
    {
        $kind = DeviceSaveState::create($request->all(['name']));
        //unset($role->permissions);
        // dd($request->get('permission'));
        //$kind->save();
        //if (is_array($request->get('permissions'))) {
        //$role->permissions()->sync($request->get('permissions',[]));
        //}
        flash_info($kind,'状态增加成功','状态增加失败');
        //event(new \App\Events\userAction('\App\Models\Kind',$kind->id,1,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}添加机构类型".$kind->name."{".$kind->id."}"));
        return redirect('/admin/devicesavestate');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $devicesavestate = DeviceSaveState::find($id);


        //$data['id'] = (int)$id;
        return view('admin.devicesavestate.edit', ['devicesavestate'=>$devicesavestate]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DeviceSaveStateRequest $request, $id)
    {
        $kind=DeviceSaveState::where('id',$id)->update($request->all(['name']));
        flash_info($kind,'状态名称修改成功','状态名称修改失败');
        //$role->permissions()->sync($request->get('permissions',[]));
        //event(new \App\Events\userAction('\App\Models\Kind',$id,3,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}编辑机构类型".$request->all(['name'])."{".$id."}"));
        return redirect('/admin/devicesavestate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result= DeviceSaveState::destroy($id);
        flash_info( $result,'状态删除成功','状态删除失败');
        return redirect()->back();
    }
}
