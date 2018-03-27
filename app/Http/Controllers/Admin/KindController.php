<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kind;
use Illuminate\Http\Request;
use App\Http\Requests\KindCreateRequest;

use App\Http\Controllers\Controller;

class KindController extends Controller
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
            $data['recordsTotal'] = Kind::count();
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = Kind::where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%');
                        //->orWhere('description', 'like', '%' . $search['value'] . '%');
                })->count();
                $data['data'] = Kind::where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%');
                        //->orWhere('description', 'like', '%' . $search['value'] . '%');
                })
                    ->skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            } else {
                $data['recordsFiltered'] = Kind::count();
                $data['data'] = Kind::skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            }
            //dd(response()->json($data));
             $data['data'] = setRow_No($data['data']);
            return response()->json($data);
        }
         //$data['recordsTotal'] = $data['recordsFiltered']=Kind::all()->count();
         //   $data['data'] = Kind::all();
         //   $data['data'] = setRow_No($data['data']);
        return view('admin.kind.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        return view('admin.kind.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KindCreateRequest $request)
    {
                        unset($request['_token']);
                        unset($request['_method']);
        $kind = Kind::create($request->all());
        //unset($role->permissions);
        // dd($request->get('permission'));
        //$kind->save();
        //if (is_array($request->get('permissions'))) {
            //$role->permissions()->sync($request->get('permissions',[]));
        //}
        flash_info($kind,'类别增加成功','类别增加失败');
        //event(new \App\Events\userAction('\App\Models\Kind',$kind->id,1,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}添加机构类型".$kind->name."{".$kind->id."}"));
        return redirect('/admin/kind')->withSuccess('添加成功！');

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
        $kind = Kind::find($id);
        

        //$data['id'] = (int)$id;
        return view('admin.kind.edit', ['kind'=>$kind]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(KindCreateRequest $request, $id)
    {
        $kind=Kind::where('id',$id)->update($request->all(['name']));
        flash_info($kind,'类别修改成功','类别修改失败');
        //$role->permissions()->sync($request->get('permissions',[]));
        //event(new \App\Events\userAction('\App\Models\Kind',$id,3,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}编辑机构类型".$request->all(['name'])."{".$id."}"));
        return redirect('/admin/kind');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //先修改为1 再删除 1在表中定义的未定义意义
        \App\Models\Institution::where('kind_id',$id)->update(['kind_id'=>1]);
        $result= Kind::destroy($id);
        flash_info( $result,'类别删除成功','类别删除失败');
        return redirect()->back();
    }
}
