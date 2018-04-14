<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Http\Requests\EmployeeCreateRequest;
use Illuminate\Support\Facades\DB;
use Overtrue\LaravelPinyin\Facades\Pinyin;
//use PhpOffice\PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


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

        //dd(implode('',Pinyin::convert('带着希望去旅行')));
        $currinstitutions=$this->getCurrInstitutions();
        //dd($currinstitutions);
        $arr = _getAllInstitutionId($currinstitutions);
        $g_in_s = implode(',',$arr);
        $mysql = "SELECT @row_no:=@row_no+1 as row_no, a.id ,a.name,a.mobile,a.sex,a.post ,a.sfz,CONCAT('[',a.institution_id ,']',b.name) as institution_name  FROM admin_employees a ,(select  @row_no:=0) t  ,admin_institutions b where a.institution_id=b.id and b.id in(%s) ";
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
                $where = " and ((a.name like '%".$search['value']."%' ) or (a.post  like '%".$search['value']."%') or (a.sfz  like '%".$search['value']."%') or (b.name  like '%".$search['value']."%') ) ";
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


    //批量生成前台用户
    public function autocreate()
    {
       // dd(storage_path(),str_replace('/storage','/app/public','/storage/uploadfile/fjoIkNtgu34yXI5sweC05ugjYtca2Zwzka0xJA1G.xlsx'));
       // dd(\Storage::url('public')); //返回路径
//设置文件读取位置
//$filePath=storage_path().'/app/public/uploadfile/fjoIkNtgu34yXI5sweC05ugjYtca2Zwzka0xJA1G.xlsx';
//$filePath=storage_path().'/app/public/uploadfile/S3j0yqj8fqsYKfhdRLXLWV6so61Uv6uPVvJvYZcZ.xls';
//读取文件的扩展名
//$extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
//$basename=   strtolower(pathinfo($filePath, PATHINFO_BASENAME));
        //dump($extension);
        //if ($extension == 'xlsx'){
            //$reader = IOFactory::createReader("Xlsx");
        //    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            //dump('1 xlsx');
        //}elseif ($extension == 'xls'){
            //$reader = IOFactory::createReader("Xls");
        //    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            //dump('2 xls');
        //}elseif ($extension == 'csv'){
        //    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        //    $reader->setInputEncoding('CP1252');
        //    $reader->setDelimiter(';');
        //    $reader->setEnclosure('');
        //    $reader->setSheetIndex(0);
        //}elseif ($extension == 'html'){
        //    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        //}

        //if($reader){
         //   $spreadsheet = $reader->load($filePath);
         //   $dataAsAssocArray = $spreadsheet->getActiveSheet();
         //   $highestRow = $dataAsAssocArray->getHighestRow(); // e.g. 10
          //  $highestColumn = $dataAsAssocArray->getHighestColumn(); // e.g 'F'
           // $highestColumn = 'F'; // e.g 'F'

         //   for ($row = 1; $row <= $highestRow; $row++) {
//
           //     for ($col = 'A'; $col <= $highestColumn; $col++) {

           //         dump($col . $row,$dataAsAssocArray->getCell($col . $row)->getValue()) ;

           //     }

            //}
            //$highestColumn++;
      //      dd($highestRow);
           // dd($dataAsAssocArray->getRowDimensions(),$dataAsAssocArray->getColumnDimensions());
 //           $rows = count($dataAsAssocArray->getRowDimensions()); //数组 行数据
            //dd($dataAsAssocArray->getCell('A6')->getValue());
            //$columns = array_keys($dataAsAssocArray->getColumnDimensions());
  //          $columns = ['A','B','C','D','E'];
            //dd($columns);
  //          for ($i=1;$i<=$rows;$i++){
   //             foreach($columns as $column){
   //                 $value = $column.$i;
                    //dump($column.$i);
   //                 dump($value,$dataAsAssocArray->getCell($value)->getValue());
                    //dump($dataAsAssocArray->getCell($column.$i)->getValue());
    //            }

   //         }


    //        $spreadsheet->disconnectWorksheets();
    //        unset($spreadsheet,$reader);
     //   }
       // dd($dataAsAssocArray);

//        //dd(__Dir__);
//        $dir = url()->current();
//        dd($dir);
//        $filePath = "../../../public/hello_world.xlsx";
//dump($filePath);
//        //dd(\Storage::disk('public'));
 //       try {
 //                  $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
 //                  $spreadsheet = $reader->load($filePath);
            //dd($spreadsheet);
            //$reader = IOFactory::createReader("Xlsx");
            //$spreadsheet = $reader->load($filePath);

//
//            //$reader = IOFactory::createReaderForFile('Xls');
//           // $spreadSheet = IOFactory::load($filePath);
//            //$spreadSheet = $reader->load($filePath);
//
  //          $dataAsAssocArray = $spreadsheet->getActiveSheet()->toArray();
  //          dd($dataAsAssocArray);
//
    //    }catch(\Exception $exception){
//
    //   }
   //     dd('1111111');
//        //$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::Open(‘/hello_world.xlsx‘);
//        //dd( $spreadsheet );
//        //$excel =   new Reader('hello_world.xlsx');
//
//        //$worksheet = $excel->read('hello_world.xlsx');
//
//        //$value=$worksheet->getCellValue(‘A1‘);
//        //dd($excel );
//
//        $spreadsheet = new Spreadsheet();
//
//        $sheet = $spreadsheet->getActiveSheet();
//        $sheet->setCellValue('A1', 'Hello World !');
//
//        $writer = new Xlsx($spreadsheet);
//
//        $writer->save('hello_world.xlsx');
//        dd($writer);

        $institutions = $this->getCurrInstitutions();

        //dd($institutions);
        // $institutionId=getCurrentInstitutionId();//获取当前用户的管理机构id
        // $temps = User::pluck('employee_id')->toarray(); //取所有已经存在的操作员信息


        //所有的本机构的人员,但是排除在前台用户表中存在的;
        //  $employees =Institution::find($institutionId)->employees()->whereNotIn('id',$temps)->get()->toarray();
        //dd($temps,$employees);
        //$temp1=array_intersect($employees,$emps);//返回交集
        //$temp2=array_diff($employees,$temp1);//返回差集
        //array_ushift($empls);
        //dump($employees,$emps,$temp1,$temp2);

        //dd( array_intersect($employees,$emps)) ;
        //User::;
        //  unset($temps);
        //  $employees = array_pluck($employees, 'name','id');
        //dd($employees);
        //dd($employees);
        //$permissions = $this->getAllPermissions();
        //$roles = $this->getAllRole();


        return view('admin.employee.autocreate', compact(['institutions']));




    }

    //批量生成前台用户
    public function autostore(Request $request)
    {
        $file_url= $request->file_url;
        $institution_id= $request->institution_id;
        $has_title= (boolean)$request->has_title;
        $has_institution= (boolean)$request->has_institution;
        if(empty($file_url)){
            flash('文件名不存在','danger')->important();
            //unlink($filePath);//删除文件
            return redirect('/admin/employee');
        }
        //dd($file_url,$institution_id,$has_title,$has_institution);
        $filePath= storage_path() . str_replace('/storage','/app/public',$file_url);
        //$basename=   strtolower(pathinfo($filePath, PATHINFO_BASENAME));
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($extension == 'xlsx'){
            //$reader = IOFactory::createReader("Xlsx");
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            //dump('1 xlsx');
        }elseif ($extension == 'xls'){
            //$reader = IOFactory::createReader("Xls");
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            //dump('2 xls');
        }elseif ($extension == 'csv'){
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            $reader->setInputEncoding('CP1252');
            $reader->setDelimiter(';');
            $reader->setEnclosure('');
            $reader->setSheetIndex(0);
        }elseif ($extension == 'html'){
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        }else{
           // dd('文件类型错误!');
            //flash_error(empty($msg));
            flash('文件类型错误!','danger')->important();
            //unlink($filePath);//删除文件
        }

        if($reader) {
            $spreadsheet = $reader->load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow(); // e.g. 10  获取行数
            //$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
            //$highestColumn = 'F'; // e.g 'F'  设置要读取的最后列
            if ($has_title) {
                $row = 2;
            } else {
                $row = 1;
            }
            $n=0;
            $msg='';
            for ($row; $row <= $highestRow; $row++) {
                //for ($col = 'A'; $col <= $highestColumn; $col++) {
                //$field = $col .$row;

                //开如数据处理
                //1 设置数组值
                $data['name'] = $worksheet->getCell('A' . $row)->getValue();
                $data['mobile'] = $worksheet->getCell('B' . $row)->getValue();
                $data['sfz'] = $worksheet->getCell('C' . $row)->getValue();
                $data['sex'] = $worksheet->getCell('D' . $row)->getValue();
                $data['post'] = $worksheet->getCell('E' . $row)->getValue();

                if ($has_institution) {
                    $data['institution_id'] = $worksheet->getCell('F' . $row)->getValue();
                } else {
                    $data['institution_id'] = $institution_id;
                }
                if(!is_numeric($data['sex'])){
                    $msg ='第'.$row.'行数据出错,'.'性别类型不符'.',已成功导入'.$n.'条数据!';
                    flash($msg,'danger')->important();
                    break;
                    //
                    //$spreadsheet->disconnectWorksheets();
                    //unset($spreadsheet,$reader);
                    //unlink($filePath);//删除文件
                    //return redirect('/admin/employee');
                }
                if(!is_numeric($data['institution_id'])){
                    $msg ='第'.$row.'行数据出错,'.'机构号类型不符'.',已成功导入'.$n.'条数据!';

                    flash($msg,'danger')->important();
                    break;
                    //$spreadsheet->disconnectWorksheets();
                    //unset($spreadsheet,$reader);
                    //unlink($filePath);//删除文件
                    //return redirect('/admin/employee');
                }
                //2 用数组的值 来高置insert语句
                $sqlinsert = 'INSERT IGNORE INTO admin_employees(name, mobile, sfz, sex, post,institution_id) VALUES ('
                    . '"' . $data['name'] . '",'
                    . '"' . $data['mobile'] . '",'
                    . '"' . $data['sfz'] . '",'
                     . $data['sex'] . ','
                    . '"' . $data['post'] . '",'
                    . $data['institution_id']
                    . ');';
               // dd( $sqlinsert);

                //3 执行语句

                try {
                    $flag = DB::insert($sqlinsert);
                    if(!$flag){

                        $msg ='第'.$row.'行数据出错,已成功导入'.$n.'条数据!';
                        flash($msg,'danger')->important();
                        //unlink($filePath);//删除文件
                        break;
                    }
                } catch (\Exception $e) {
                    $msg=$e->getMessage();
                    flash('导入数据出错!','danger')->important();
                    //unlink($filePath);//删除文件
                    break;
                    //exit();
                }
                $n=$n+1;

            }
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet,$reader);
            if($highestRow == $row-1){//如果行与表格行数相同，则为全部导入成功
                $msg ='已成功导入'.$n.'条全部数据!';
                flash($msg,'success')->important();
            }

        }

                //$spreadsheet->disconnectWorksheets();
                //unset($spreadsheet,$reader);
        //if(!empty($msg)){ $this->error($msg,'',true);}
        //$this->success($n.'条数据已导入成功！', '', true) ;
        //flash_info(empty($msg));
        unlink($filePath);//删除文件
        return redirect('/admin/employee');
        //return view('admin.fuser.autocreate', compact(['institutions']));




    }

//批量删除前台用户页面
    public function autodeleteview()
    {

        $institutions = $this->getCurrInstitutions();

        //dd($institutions);
        // $institutionId=getCurrentInstitutionId();//获取当前用户的管理机构id
        // $temps = User::pluck('employee_id')->toarray(); //取所有已经存在的操作员信息


        //所有的本机构的人员,但是排除在前台用户表中存在的;
        //  $employees =Institution::find($institutionId)->employees()->whereNotIn('id',$temps)->get()->toarray();
        //dd($temps,$employees);
        //$temp1=array_intersect($employees,$emps);//返回交集
        //$temp2=array_diff($employees,$temp1);//返回差集
        //array_ushift($empls);
        //dump($employees,$emps,$temp1,$temp2);

        //dd( array_intersect($employees,$emps)) ;
        //User::;
        //  unset($temps);
        //  $employees = array_pluck($employees, 'name','id');
        //dd($employees);
        //dd($employees);
        //$permissions = $this->getAllPermissions();
        //$roles = $this->getAllRole();


        return view('admin.employee.autodeleteview', compact(['institutions']));




    }
    //批量删除前台用户
    public function autodelete(Request $request)
    {
        //dd(substr('420803197511055111',-4,4));

        $institution_id = $request->institution_id;//获取机构id
        $institutions = getInstitutionList($institution_id);
        //dd(array_pluck($institutions,'id'));

        $institution_ids=array_keys(_getAllInstitutionIdNameLevel($institutions));

        //$temps = array_sort(User::pluck('employee_id')->toarray()); //取所有已经存在的操作员信息
        //$temps[]=30;
        //dump($temps);
        // array_add($temps,12,20);
        $retu = Employee::wherein('institution_id',$institution_ids)->delete();
        //dd($employees);
        //$temp2=array_intersect($employees,$temps);//返回交集 这个数组是可以更新的id
        //dump($temp2);
        //$retu=Employee::destroy($employees); //创建用户并保存
        //dd($retu);
        flash_info($retu,'员工删除成功','员工删除失败');

//dd($institution_ids,$employees,$temps,array_diff($employees,$temps));
        //所有的本机构的人员,但是排除在前台用户表中存在的;
        //  $employees =Institution::find($institutionId)->employees()->whereNotIn('id',$temps)->get()->toarray();
        //dd($temps,$employees);
        //$temp1=array_intersect($employees,$emps);//返回交集
        //$temp2=array_diff($employees,$temp1);//返回差集
        //array_ushift($empls);
        //dump($employees,$emps,$temp1,$temp2);

        //dd( array_intersect($employees,$emps)) ;


        //flash_info(1,'前台用户增加成功','前台用户增加失败');
        //Event::fire(new permChange());
        //event(new \App\Events\userAction('\App\Models\User', $user->id, 1, '添加了前台用户' . $user->name));
        return redirect('/admin/employee');
        //return view('admin.fuser.autocreate', compact(['institutions']));




    }



    //批量删除前台用户页面
    public function downloadview()
    {

        $institutions = $this->getCurrInstitutions();

        //dd($institutions);
        // $institutionId=getCurrentInstitutionId();//获取当前用户的管理机构id
        // $temps = User::pluck('employee_id')->toarray(); //取所有已经存在的操作员信息


        //所有的本机构的人员,但是排除在前台用户表中存在的;
        //  $employees =Institution::find($institutionId)->employees()->whereNotIn('id',$temps)->get()->toarray();
        //dd($temps,$employees);
        //$temp1=array_intersect($employees,$emps);//返回交集
        //$temp2=array_diff($employees,$temp1);//返回差集
        //array_ushift($empls);
        //dump($employees,$emps,$temp1,$temp2);

        //dd( array_intersect($employees,$emps)) ;
        //User::;
        //  unset($temps);
        //  $employees = array_pluck($employees, 'name','id');
        //dd($employees);
        //dd($employees);
        //$permissions = $this->getAllPermissions();
        //$roles = $this->getAllRole();


        return view('admin.employee.downloadview', compact(['institutions']));




    }


    //生成并下载 员工数据
    public function download(Request $request)
    {

        $institution_id = $request->institution_id;//获取机构id
        $institutions = getInstitutionList($institution_id);
        //dd(array_pluck($institutions,'id'));

        $institution_ids=array_keys(_getAllInstitutionIdNameLevel($institutions));
        $g_in_s = implode(',',$institution_ids);
        $mysql = "SELECT @row_no:=@row_no+1 as row_no, a.id ,a.name,a.mobile,ELT(sex +1, '女','男') as sex ,a.post ,a.sfz,CONCAT('[',a.institution_id ,']',b.name) as institution_name  FROM admin_employees a ,(select  @row_no:=0) t  ,admin_institutions b where a.institution_id=b.id and b.id in(%s) order by a.institution_id asc,a.id asc ";
        //$countmysql = "select count(id) as total from  (%s) z";

        $mysql = sprintf($mysql,$g_in_s);
        //$countmysql = sprintf($countmysql,$mysql);

        $datas =DB::select($mysql);
        //dd($datas);

        if($datas){
            $filename = './storage/download/employees'.date('YmdHis', time()).'.xlsx';//设置保存文件名
            $spreadsheet = new Spreadsheet();
            $worksheet = $spreadsheet->getActiveSheet();
            $highestColumn = 'A'; // e.g 'F'  设置要读取的最后列
            $worksheet->setCellValue('A1', '行号');
            $worksheet->setCellValue('B1', '编号');
            $worksheet->setCellValue('C1', '姓名');
            $worksheet->setCellValue('D1', '电话');
            $worksheet->setCellValue('E1', '身份证号');
            $worksheet->setCellValue('F1', '所在机构');
            $worksheet->setCellValue('G1', '岗位');
            foreach ($datas as $k=>$data){
                  $n=$k+2;
                $worksheet->setCellValue('A'.$n, $k+1);
                $worksheet->setCellValue('B'.$n, $data->id);
                $worksheet->setCellValue('C'.$n, $data->name);
                $worksheet->setCellValueExplicit(
                    'D'.$n,
                    $data->mobile,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );
                //$worksheet->setCellValue('D'.$n, $data->mobile,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $worksheet->setCellValueExplicit(
                    'E'.$n,
                    $data->sfz,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );
                //$worksheet->setCellValue('E'.$n, $data->sfz,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $worksheet->setCellValue('F'.$n, $data->institution_name);
                $worksheet->setCellValue('G'.$n, $data->post);
               //dump( 'A'.$n);
               // dump($data->name);
            }
            //$worksheet->getStyle('D')->getAlignment()->setWrapText(true);
            //$worksheet->getStyle('E')->getAlignment()->setWrapText(true);


            $writer = new Xlsx($spreadsheet);//

            $writer->save($filename);
            //dd($spreadsheet);
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet,$worksheet,$writer);
            $basename=   strtolower(pathinfo($filename, PATHINFO_BASENAME));
            return response()->download($filename, $basename)->deleteFileAfterSend(true);//下载完成后删除
        }

        //flash('导出成功','success')->important();

        //$spreadsheet->disconnectWorksheets();
        //unset($spreadsheet,$reader);
        //if(!empty($msg)){ $this->error($msg,'',true);}
        //$this->success($n.'条数据已导入成功！', '', true) ;
        //flash_info(empty($msg));

        //return redirect('/admin/employee');
        //return view('admin.fuser.autocreate', compact(['institutions']));




    }

}
