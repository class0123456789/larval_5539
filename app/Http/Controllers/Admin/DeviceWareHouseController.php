<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DeviceWareHouse;
use App\Http\Requests\DeviceWareHouseRequest;
use Illuminate\Support\Facades\DB;

use App\Models\DeviceModel;
use App\Models\DeviceClass;
use App\Models\DeviceSupplier;
use App\Models\DeviceBrand;
use App\Models\MaintenanceProvider;
use App\Models\FinancialApproval;
use App\Models\DeviceEquipmentUse;
use App\Api\Helpers\Api\ApiResponse;
class DeviceWareHouseController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $house_id = getCurrentInstitutionId(); //获取仓库id

        //$curr_deviceclasses=$this->getCurrDeviceClasses();
        //dd($curr_deviceclasses);
        //$arr = _getAllInstitutionId($curr_deviceclasses);
        //$arr = array_first($arr);
       // array_shift($arr);//移除顶级
        //dd($arr);
        //$g_in_s = implode(',',$arr);

        //$mysql = "SELECT @row_no:=@row_no+1 as row_no, a.id ,a.name ,a.hardconfig,CONCAT('[',a.brand_id ,']',b.name) as brand_name,CONCAT('[',a.class_id,']',c.name) as class_name FROM device_model a ,(select  @row_no:=0) t  ,device_brand b,device_class c where a.brand_id=b.id and a.class_id=c.id  and c.id in(%s) ";
        //$countmysql = "select count(id) as total from  (%s) z";


        $mysql = "SELECT @row_no:=@row_no+1 as row_no, a.* FROM device_warehouses a ,(select  @row_no:=0) t   where a.house_id  in(%s)  ";
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
                $mysql = sprintf($mysql,$house_id);
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
                $where = " and ((a.serial_number like '%".$search['value']."%') or (a.device_macaddr like '%".$search['value']."%')) or
               (a.start_date like '%".$search['value']."%')  or (a.over_date like '%".$search['value']."%') ";
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
        //return view('admin.devicemodel.index',compact('curr_deviceclasses'));
        return view('admin.warehouse.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $house_id = getCurrentInstitutionId();
        $devicebrands = DeviceBrand::all()->toArray();//获取所有品牌
        $deviceclasses = $this->getCurrDeviceClasses();//获取所有分类
        //array_keys($deviceclasses)
        //dd($devicebrands,$deviceclasses,array_keys($deviceclasses));
        //获取默认的第一个品牌 与第一个分类中  所有可选择的设备型号
        //获取型号初始选择项
        $devicemodels = DeviceModel::where('brand_id',$devicebrands[0]['id'])->whereIn('class_id',array_keys($deviceclasses))->get()->toarray();
        //dd($devicebrands,$deviceclasses,$devicemodel);

        $devicesuppliers = DeviceSupplier::all()->toArray();//获取所有供应商
        $maintenanceproviders = MaintenanceProvider::all()->toArray();//获取所有维保商
        //dd($maintenanceproviders);

        //获取本仓库的文件
        $financialapprovals =FinancialApproval::where('house_id', $house_id)->orderBy('created_at', 'desc')->get()->toArray();

        //获取业务接入类型
        $equipmentuses = DeviceEquipmentUse::all()->toArray();


        //$devicemodels = DeviceModel::all()->toArray(); //获取所有型号   这里由选择好 品牌 分类 来 ajax 来获取可选的设备型号  因为这里是所有的分类 所以可以把这个型号可以先写上
        //
        //dd($devicebrands,$deviceclasses,$devicemodels,$devicesuppliers,$maintenanceproviders,$financialapprovals,$equipmentuses);

        return view('admin.warehouse.create',compact('devicebrands','devicesuppliers','maintenanceproviders','deviceclasses','devicemodels','financialapprovals','equipmentuses' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $house_id = getCurrentInstitutionId(); //获取仓库id
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

    public function  getCurrDeviceClasses()
    {

        //$g_institutions = $this->getInstitutionList();//得到当前用户所有可操作的机构

        //$pid=getCurrentPermission(getUser('admin'))['pid'];

        //$institutionId=getCurrentInstitutionId();//获取当前用户的管理机构id
        $temp_deviceclasses = DeviceClass::all()->toArray();
        $deviceclasses=sortInstitution($temp_deviceclasses,1); //所有分类
        //dump($institutions);
        $curr_deviceclasses=_getAllInstitutionIdNameLevel($deviceclasses);//取所有的id=>name组成的一级数组
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
        return $curr_deviceclasses;
        //return response()->json($curr_institutions);
        // return $this->message($curr_institutions);

    }
    //取指定的子级分类
    public function  getSubClasses($pclass_id)
    {

        //$g_institutions = $this->getInstitutionList();//得到当前用户所有可操作的机构

        //$pid=getCurrentPermission(getUser('admin'))['pid'];

        //$institutionId=getCurrentInstitutionId();//获取当前用户的管理机构id
        $temp_deviceclasses = DeviceClass::all()->toArray();
        $deviceclasses=sortInstitution($temp_deviceclasses,$pclass_id); //所有分类
        //dd($deviceclasses);
        //$tmp=_getchildInstitution($deviceclasses,$pclass_id);


        //dd($deviceclasses,$tmp);
        $curr_deviceclasses=_getAllInstitutionIdNameLevel($deviceclasses);//取所有的id=>name组成的一级数组
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
        return $curr_deviceclasses;
        //return response()->json($curr_institutions);
        // return $this->message($curr_institutions);

    }
    //根据选择的品牌, 分类 获取
    public  function  ajaxbrand(Request $request){

        $brand_id=$request->brand_id;
        $class_id=$request->class_id;
        //dd($class_id,$brand_id);
        $c_class=$this->getSubClasses($class_id);//取所有的子分类
        $inclass = array_keys($c_class);
        //dd($brand_id,$class_id,$c_class);

        $devicemodel=DeviceModel::where(['brand_id'=>$brand_id])->whereIn('class_id',$inclass)->orderby('class_id','asc')->get()->toarray();
        //dd($brand_id);
        return $this->success( $devicemodel);//ApiResponse在此接口中
    }





    //根据选择的品牌 获取
    public  function  showfile(Request $request) {
        //dd($request->all('id'));
        //return $this->success('11111');//ApiResponse在此接口中
        $id=$request->all('id');
        $financialapproval = FinancialApproval::find($id)->first();
        //dd($financialapproval->file_no);
        //$brands = array_pluck($brands, 'name','id');
        //$devicemodel = DeviceModel::find($id);
        //dd($model->brand->name);
        //dd($model->deviceclass->name);
        //return view('admin.financialapproval.show',);
        return view('admin.warehouse.showfile',compact('financialapproval'));
    }
    //旧设备补录
    public  function  supplement(Request $request) {
        //登记设备表、设备使用配置表
        dd('补录旧设备');
    }

    //设备申请领用
    public  function  applytouse(Request $request) {
        dd('设备申请领用');
    }

    //对设备申请领用 的审核
    public  function  applicationreview(Request $request) {
        dd('对设备申请领用 的审核处理');
    }

    //设备调出/入  borrowing
    public  function  redeployment(Request $request) {
        //登记设备表、设备使用配置表
        dd('设备调出/入');
    }

    //设备借出
    public  function  borrowing(Request $request) {
        //登记设备表、设备使用配置表
        dd('设备出借');
    }

    //设备归还
    public  function  equipmentreturn(Request $request) {
        //登记设备表、设备使用配置表
        dd('设备归还');
    }
}
