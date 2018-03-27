<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DeviceClass;
use App\Http\Requests\DeviceClassRequest;

class DeviceClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deviceclasses = DeviceClass::all()->toArray();//得到所有分类
        $deviceclasses=sortInstitution($deviceclasses,1);
        //dd(sortInstitution($deviceclasses,0));
        //dd($institutions);
        return view('admin.deviceclass.index')->with(compact('deviceclasses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $deviceclasses = DeviceClass::all()->toArray();//得到所有分类
        $deviceclasses=sortInstitution($deviceclasses,1);
        //dd($deviceclasses);
        return view('admin.deviceclass.create')->with(compact('deviceclasses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeviceClassRequest $request)
    {
        try {
            $result = DeviceClass::create($request->all());
            //dump($result);

            $res = [
                'status' => true,
                'message' => $result ? '增加成功':'增加失败',
            ];
        } catch (Exception $e) {
            //dd($e);
            $res= [
                'status' => false,
                'message' =>$e->getMessage(),
            ];
        }
        //$result = $this->service->store($request->all());
        return response()->json($res);
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
            $deviceclasses = DeviceClass::all()->toArray();//得到所有分类
            $deviceclasses=sortInstitution($deviceclasses,1);
            $deviceclasses=_getAllInstitutionIdNameLevel($deviceclasses);
            //dd($deviceclasses);
            $deviceclass= DeviceClass::find($id);

            return view('admin.deviceclass.show')->with(compact('deviceclasses', 'deviceclass'));
            //return compact('menus', 'menu');
        } catch (Exception $e) {
            //abort(500);
            flash($e->getMessage(), 'danger');
            redirect('/admin/deviceclass');
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
            $deviceclasses = DeviceClass::all()->toArray();//得到所有分类
            $deviceclasses=sortInstitution($deviceclasses,1);
            //dump($institutions);
            _deleteChildInstitution($deviceclasses,$id);//删除指定id机构及子级
            //dump(array_pluck($institutions,'id'));
            //dump(array_values(_getAllInstitutionId($institutions)));


            $deviceclass = DeviceClass::find($id);
//                        $pid=getCurrentPermission(getUser('admin'))['pid'];
//                        $in=_reSort($institutions,$pid);//返回一维数组格式数据
//                        $arr=explode(',', $id.','._subchildren($in,$id));
//                        //dd($in,$arr,$institutions);
//                        //$collection = collect($institutions);
//                        //dd($collection);
//                        //去除本机构及子机构
//                        //$filtered = $institutions->reject(function ($value, $key) use($arr) {
//                        //            return in_array($value->id,$arr);
//                        //});
//                        $att=[];
//                        foreach ($in as $k => $v) {
//                            if(!in_array($v['id'],$arr)){
//                               $att[]= $v;
//                            }
//                        }
//                        $sorted = array_values(array_sort($att, function ($value) {
//                            return $value['parent_id'];
//                        }));
//                        //dump($sorted);
//                        //dd(min(array_pluck($sorted,'parent_id')),max(array_pluck($sorted,'parent_id')));
//                        $minpid=\min(array_pluck($sorted,'parent_id'));
//                        //$maxpid=\max(array_pluck($sorted,'parent_id'));
//                       // dd(treeData($sorted,$minpid ));
//                        //dd($sorted);
//
//                        // dd($sorted,$arrtt);
//
//                        $institutions= treeData($sorted,$minpid );//树型结构数据
//                        dump($institutions,$institution);
//                        unset($arr,$in,$att,$sorted,$minpid);
            //dd($institutions);
            //dd( $institutions,$filtered->all(),$arr);
            //dd($institutions,$institutions->where('id','in',$arr));
            //dd($institutions);
            //$this->clearSelf($institutions, $id);
            //dd($institutions);

            //return array_merge($attr, compact('permissions'));
            return view('admin.deviceclass.edit')->with(compact('deviceclasses','deviceclass'));
        } catch (Exception $e) {
            //abort(500);
            flash($e->getMessage(), 'danger');
            redirect('/admin/deviceclass');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DeviceClassRequest $request, DeviceClass $deviceclass)
    {
        try {

            $isUpdate = $deviceclass->update($request->all());

            $result= [
                'status' => $isUpdate,
                'message' => $isUpdate ? '更新成功':'更新失败',
            ];
        } catch (Exception $e) {
            $result= [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
        //$result = $this->service->update($request->all(), $id);
        //dd($result);
        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            //$institutions = $this->getInstitutionList();//树型结构数据
            //$institutions = getInstitutionList($id); //获取指定机构及子级

            $deviceclasses = DeviceClass::all()->toArray();//得到所有分类
            $deviceclasses=sortInstitution($deviceclasses,$id);
            //dd($deviceclasses);
            //有子分类不能删除
            if($deviceclasses[0]['child']){
                flash('存在子级分类不能删除', 'danger');
            }else{
                $result = DeviceClass::where('id',$id)->delete();//删除自已及子机构
                flash_info($result,'删除成功','删除失败');
            }


            // \App\Models\Employee::institutions()->wherePivotIn('institution_id', $arr)->detach();
            //unset($institutions,$in,$arr);
            //$result=true;
            //$result = Menu::delete($id);
            //dd($result);


        } catch (Exception $e) {
            //dd($e->getMessage());
            flash($e->getMessage(), 'danger');
        }
        //$this->service->destroy($id);
        return redirect()->route('deviceclass.index');
    }
}
