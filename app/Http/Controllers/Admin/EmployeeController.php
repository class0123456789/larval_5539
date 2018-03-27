<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Http\Requests\EmployeeCreateRequest;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd($request);
        $currinstitutions=$this->getCurrInstitutions();
        //dd($currinstitutions);
        $arr = _getAllInstitutionId($currinstitutions);
        $g_in_s = implode(',',$arr);
        $mysql = "SELECT @row_no:=@row_no+1 as row_no, a.id ,a.name,a.mobile,a.sex,a.post ,CONCAT('[',a.institution_id ,']',b.name) as institution_name  FROM admin_employees a ,(select  @row_no:=0) t  ,admin_institutions b where a.institution_id=b.id and b.id in(%s) ";
        $countmysql = "select count(id) as total from  (%s) z";
//        if ($request->ajax()) {
//            //dd('is ajax');
//            $data = array();
//            $data['draw'] = $request->get('draw');
//            $start = $request->get('start');
//            $length = $request->get('length');
//            $order = $request->get('order');
//            $columns = $request->get('columns');
//            $search = $request->get('search');
//            $data['recordsTotal'] = Employee::count();
//            if (strlen($search['value']) > 0) {
//                $data['recordsFiltered'] = Employee::where(function ($query) use ($search) {
//                    $query->where('name', 'LIKE', '%' . $search['value'] . '%');
//                        //->orWhere('description', 'like', '%' . $search['value'] . '%');
//                })->count();
//                $data['data'] = Employee::where(function ($query) use ($search) {
//                    $query->where('name', 'LIKE', '%' . $search['value'] . '%');
//                        //->orWhere('description', 'like', '%' . $search['value'] . '%');
//                })
//                    ->skip($start)->take($length)
//                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
//                    ->get();
//            } else {
//                $data['recordsFiltered'] = Employee::count();
//                $data['data'] = Employee::skip($start)->take($length)
//                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
//                    ->get();
//            }
//            //dd(response()->json($data));
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
                $where = " and ((a.name like '%".$search['value']."%' ) or (a.post  like '%".$search['value']."%')  or (b.name  like '%".$search['value']."%') ) ";
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

//         $data['recordsTotal'] = $data['recordsFiltered']=Employee::all()->count();
//            $data['data'] = Employee::all();
//            $data['data'] = setRow_No($data['data']);
        return view('admin.employee.index',compact('currinstitutions'));
        //return view('admin.employee.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $institutions = $this->getCurrInstitutions();
         //dd($institutions);

        return view('admin.employee.create',compact(['institutions']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeCreateRequest $request)
    {
                       // unset($request['_token']);
                       // unset($request['_method']);
        $employee = Employee::create($request->all());
        
        //if (isset($request['institution_id'])) {
        //    $employee->institutions()->attach($request['institution_id']);
        //}
        //unset($role->permissions);
        // dd($request->get('permission'));
        //$employee->save();
        //if (is_array($request->get('permissions'))) {
            //$role->permissions()->sync($request->get('permissions',[]));
        //}
        flash_info($employee,'员工增加成功','员工增加失败');
        //event(new \App\Events\userAction('\App\Models\Employee',$employee->id,1,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}添加机构类型".$employee->name."{".$employee->id."}"));
        return redirect('/admin/employee');

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
        $employee = Employee::find($id);
        $institutions = $this->getCurrInstitutions();
        

        //$data['id'] = (int)$id;
        return view('admin.employee.edit', compact(['employee','institutions']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeCreateRequest $request,Employee $employee)
    {
        //dd($employee->get('id'));
        
        try {
			$result=$employee->update($request->all());
                        //dd($result,request['permission']);
                       
                        //dd($permissions);

			  flash_info($employee,'员工修改成功','员工修改失败');
                       // Event::fire(new permChange());
                       // event(new \App\Events\userAction('\App\Models\Role',$role->id,3,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}编辑角色".$role->name."{".$role->id."}"));
                        //return redirect('/admin/role')->withSuccess('修改成功！');
			return redirect('/admin/employee');
		} catch (Exception $e) {
			//flash('角色修改出错', 'danger');
                         flash($e->getMessage(), 'danger');
			return redirect('/admin/role');
		}

        //$role->permissions()->sync($request->get('permissions',[]));
        //event(new \App\Events\userAction('\App\Models\Employee',$id,3,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}编辑机构类型".$request->all(['name'])."{".$id."}"));
       // return redirect('/admin/employee')->withSuccess('修改成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //$employee->institutions()->detach();//删除人员机构表
        //先修改为1 再删除 1在表中定义的未定义意义
        // \App\Models\Institution::where('employee_id',$id)->update(['employee_id'=>1]);
        //\App\Models\User::where('employee_id',$employee->id)->delete(); //删除对应的前台用户
        $employee->user()->delete();//删除对应的前台用户
        $result= $employee->delete();
        flash_info( $result,'员工删除成功','员工删除失败');
        return redirect()->back();
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
                        $pid=getCurrentPermission(getUser('admin'))['pid'];
                        $curr_institution= \App\Models\Institution::find($pid)->toArray();
			$institutionList = $this->sortInstitution($institutions,$pid);
                        $arr=[];
                        
                        //$arr[0]['child'] = $institutionList;
                        //dd($arr);
                        $curr_institution['child'] = $institutionList; 
                        $arr[] = $curr_institution;
                        //dump($institutionList,$curr_institution);
                        $institutionList= $arr; 
                         //dd($institutionList,$curr_institution);
                        unset($curr_institution,$pid,$arr);
			foreach ($institutionList as $key => &$v) {
				if ($v['child']) {
					$sort = array_column($v['child'], 'id');
					array_multisort($sort,SORT_DESC,$v['child']);
				}
			}
                        //dd($institutionList,$curr_institution);
			// 缓存菜单数据
			cache()->forever('institutionList',$institutionList);
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
}
