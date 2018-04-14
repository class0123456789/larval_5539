<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HouseInstitution;
use Illuminate\Support\Facades\DB;

class HouseInstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $institution_id = getCurrentInstitutionId(); //获取仓库id
        $institutions = getInstitutionList($institution_id);
        $institutions = array_keys(_getAllInstitutionIdNameLevel($institutions));
        $institutionids=implode('，',$institutions);
        //dd($institutionids);


        //$curr_deviceclasses=$this->getCurrDeviceClasses();
        //dd($curr_deviceclasses);
        //$arr = _getAllInstitutionId($curr_deviceclasses);
        //$arr = array_first($arr);
        // array_shift($arr);//移除顶级
        //dd($arr);
        //$g_in_s = implode(',',$arr);

        //$mysql = "SELECT @row_no:=@row_no+1 as row_no, a.id ,a.name ,a.hardconfig,CONCAT('[',a.brand_id ,']',b.name) as brand_name,CONCAT('[',a.class_id,']',c.name) as class_name FROM device_model a ,(select  @row_no:=0) t  ,device_brand b,device_class c where a.brand_id=b.id and a.class_id=c.id  and c.id in(%s) ";
        //$countmysql = "select count(id) as total from  (%s) z";


        $mysql = "SELECT @row_no:=@row_no+1 as row_no, a.* FROM house_institution a ,(select  @row_no:=0) t   where a.institution_id  in('"."%s"."')  ";
        $countmysql = "select count(id) as total from  (%s) z";

        if ($request->ajax()) {
            //1 取得当前用户可以操作的机构

            //2 查询出 在上面查询出的机构的中 所有员工
            //3 查询出 上面得出的员工 对应的 操作员
            //$users = DB::select('select * from users where active = ?', [1]);
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $order = $request->get('order');
            $columns = $request->get('columns');
            $search = $request->get('search');
            if(!isset($request['jgh'])){
                $cid=1;
            }else{
                $cid = $request['jgh'];
            }

            if($cid==1){
                //$cid = 0; //全部
                //$mysql = sprintf($mysql,$g_in_s);
                $mysql = sprintf($mysql, $institutionids);
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
                //$where = " and ((a.name like '%".$search['value']."%' )  or (a.hardconfig  like '%".$search['value']."%') or (b.name  like '%".$search['value']."%') or (c.name  like '%".$search['value']."%')) ";
                $where = " and ((a.serial_number_id like '%".$search['value']."%') or (a.ipaddr like '%".$search['value']."%')) or
               (a.work_date like '%".$search['value']."%')  or (a.device_save_addr like '%".$search['value']."%') or (a.device_software_config like '%".$search['value']."%')";
                $mysql = $mysql . $where;
                $countmysql = "select count(id) as total from  (". $mysql. ") z";
                $cout = DB::select($countmysql);
                $data['recordsFiltered']=array_shift($cout)->total;
                $limit = " limit " . $start .", ". $length;
                $orderby = " order by row_no asc, ".$columns[$order[0]['column']]['data'] . " ". $order[0]['dir'];
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
                $orderby = " order by row_no asc, ".$columns[$order[0]['column']]['data'] . " ". $order[0]['dir'];
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
        return view('admin.houseinstitution.index',compact('institutions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
