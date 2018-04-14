<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FinancialApproval;
use App\Http\Requests\FinancialApprovalRequest;
use Illuminate\Support\Facades\DB;

class FinancialApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $currinstitutions=$this->getCurrInstitutions();
        //dd($currinstitutions);
        $arr = _getAllInstitutionId($currinstitutions);
        $g_in_s = implode(',',$arr);
        $mysql = "SELECT @row_no:=@row_no+1 as row_no, a.id ,a.file_no,a.file_url,a.created_at,CONCAT('[',a.house_id ,']',b.name) as institution_name  FROM device_financial_approval a ,(select  @row_no:=0) t  ,admin_institutions b where a.house_id=b.id and b.id in(%s) ";
        $countmysql = "select count(id) as total from  (%s) z";

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
                $where = " and ((a.file_no like '%".$search['value']."%' ) or (a.file_url  like '%".$search['value']."%')  or (b.name  like '%".$search['value']."%') ) ";
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

            } else {

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
        //$data['recordsTotal'] = $data['recordsFiltered']=Kind::all()->count();
        //   $data['data'] = Kind::all();
        //   $data['data'] = setRow_No($data['data']);
        return view('admin.financialapproval.index',compact('currinstitutions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //这里要选择下 文件所对应的发文法人 这里要显增出自已管理的机构 来选一个机构来 对应 一般是法人行 社

        $pid = getCurrentInstitutionId();
        $institutions=getInstitutionList($pid);//取本用户所管理的所有机构信息

        //$institutionId=getCurrentInstitutionId();//获取当前用户的管理机构id

        //dd($institutions);
        return view('admin.financialapproval.create',compact('institutions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FinancialApprovalRequest $request)
    {
        $house_id = getCurrentInstitutionId();
        $data=$request->all(['file_no','file_url']);
        $data['house_id'] = $house_id;
        //dd($data);
        $retu = FinancialApproval::create($data);
        flash_info($retu,'批文增加成功','批文增加失败');
        //event(new \App\Events\userAction('\App\Models\Employee',$employee->id,1,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}添加机构类型".$employee->name."{".$employee->id."}"));
        return redirect('/admin/financialapproval');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $financialapproval = FinancialApproval::find($id);
        //$brands = array_pluck($brands, 'name','id');
        //$devicemodel = DeviceModel::find($id);
        //dd($model->brand->name);
        //dd($model->deviceclass->name);
        return view('admin.financialapproval.show',compact('financialapproval'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$curr_deviceclasses=$this->getCurrDeviceClasses();
        $financialapproval = FinancialApproval::find($id);
        //$brands = array_pluck($brands, 'name','id');
        //$devicemodel = DeviceModel::find($id);
        //dd($model->brand->name);
        //dd($model->deviceclass->name);
        return view('admin.financialapproval.edit',compact('financialapproval'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FinancialApprovalRequest $request, $id)
    {
        $ret=FinancialApproval::find($id)->update($request->all(['file_no','file_url']));
        flash_info($ret,'文号修改成功','文号修改失败');
        //$role->permissions()->sync($request->get('permissions',[]));
        //event(new \App\Events\userAction('\App\Models\Kind',$id,3,"用户".auth('admin')->user()->name."{".auth('admin')->user()->id."}编辑机构类型".$request->all(['name'])."{".$id."}"));
        return redirect('/admin/financialapproval');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret=FinancialApproval::destroy($id);
        flash_info($ret,$id.'文号删除成功',$id.'文号删除失败');
        return redirect('/admin/financialapproval');


    }

    public function  getCurrInstitutions()
    {

        //$g_institutions = $this->getInstitutionList();//得到当前用户所有可操作的机构

        //$pid=getCurrentPermission(getUser('admin'))['pid'];

        $institutionId=getCurrentInstitutionId();//获取当前用户的管理机构id
        $institutions = getInstitutionList($institutionId);//得到当前用户的机构及子级机构
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
