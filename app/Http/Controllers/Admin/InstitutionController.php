<?php

namespace App\Http\Controllers\Admin;

use App\Models\Institution;
use App\Models\Kind;
use App\Http\Requests\InstitutionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Exception;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *  机构控制器
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $pid=getCurrentInstitutionId();
         //$institutions = $this->getInstitutionList();
         $institutions = getInstitutionList($pid);
         //dd($institutions);
        return view('admin.institution.index')->with(compact('institutions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $institutionId=getCurrentInstitutionId();//获取当前用户的管理机构id
        $institutions = getInstitutionList($institutionId);
        //$institutions = $this->getInstitutionList();
        //dd($institutions);
	     $kinds = Kind::all(['id', 'name']);
        // dd($menus);
		//return compact('menus', 'permissions');
        return view('admin.institution.create')->with(compact('institutions', 'kinds'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InstitutionRequest $request)
    {
        try {
			$result = Institution::create($request->all());
                        //dump($result);
			if ($result) {
				// 更新缓存
				//$this->sortInstitutionSetCache();
                            //重新缓存所有机构
                            SetInstitutionCache();
			}
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
                        $institutionId=getCurrentInstitutionId();//获取当前用户的管理机构id
			$institutions = getInstitutionList($institutionId);
			$institution= Institution::find($id);
                        $kinds = Kind::all(['id', 'name']);
                        return view('admin.institution.show')->with(compact('institutions', 'institution','kinds'));
			//return compact('menus', 'menu');
		} catch (Exception $e) {
			//abort(500);
                        flash($e->getMessage(), 'danger');
                        redirect('/admin/institution');
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
			//$attr = $this->show($id);
//                        $institutions = $this->getInstitutionList();//树型结构数据
                                $institutionId=getCurrentInstitutionId();//获取当前用户的管理机构id
                                $institutions = getInstitutionList($institutionId);
                                //dump($institutions);
                                _deleteChildInstitution($institutions,$id);//删除指定id机构及子级
                                //dump(array_pluck($institutions,'id'));  
                                //dump(array_values(_getAllInstitutionId($institutions)));


			$institution = Institution::find($id);
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
			$kinds = Kind::all(['id', 'name']);
			//return array_merge($attr, compact('permissions'));
                         return view('admin.institution.edit')->with(compact('kinds', 'institutions','institution'));
            } catch (Exception $e) {
			//abort(500);
                flash($e->getMessage(), 'danger');
                redirect('/admin/institution');
	}
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InstitutionRequest $request, Institution $institution)
    {
        try {    
                       
			$isUpdate = $institution->update($request->all());
			if ($isUpdate) {
				// 更新缓存
				//$this->sortInstitutionSetCache();
                             //重新缓存所有机构
                              SetInstitutionCache();
			}
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
                        $institutions = getInstitutionList($id); //获取指定机构及子级
                        //dd($institutions);
             //有子分类不能删除
             if($institutions[0]['child']){
                 flash('存在子级机构不能删除', 'danger');
             }else{
                 $result = Institution::where('id',$id)->delete();//删除自已及子机构
                 if ($result) {
                     //$this->sortInstitutionSetCache();
                     //重新缓存所有机构
                     SetInstitutionCache();
                 }
                 flash_info($result,'删除成功','删除失败');
             }

                         //dd($institutions,$id);
                        // //dump(array_values(_getAllInstitutionId($institutions)));
                //        $arr=_getAllInstitutionId($institutions);//取所有的id组成的一级数组
                        //dd($institutions);
                       //1 $in=_reSort($institutions);//返回一维数数格式数据
                        //if($in==''){
                        //    $arr = [ $id];
                        //}else{
                       //2    $arr=explode(',', $id.','._subchildren($in,$id)); 
                        //}
                        
                        //dd($arr);
                      //  dd(Institution::employees()->wherePivotIn('institution_id', $arr));
                        //Institution::employees()->wherePivotIn('institution_id', $arr)->detach();//删除人员信息
//                       $institutionss = Institution::wherein('id',$arr)->get();
//                       if($institutionss->isNotEmpty()){
//                           foreach ($institutionss as $institution){
//                               $institution->employees()->detach(); //把中间表中的记录删了
//                           }
//
//                       }
                       //dd($arr,$institutionss);
			          // $result = Institution::wherein('id',$arr)->delete();//删除自已及子机构
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
        return redirect()->route('institution.index');
    }
    
    public function cacheClear()
    {
	        // cache()->forget('institutionList');
                //重新缓存所有机构
                 cacheClear('allInstitutions');
                //flash_info($result,'权限删除成功','权限删除失败');
		         flash('机构清理成功', 'success');
                 return redirect()->route('institution.index');
    }
    
//    public function getInstitutionList()
//	{
//		// 判断数据是否缓存
//		//if (cache()->has('institutionList')) {
//		//	return cache()->get('institutionList');
//		//}
//		return $this->sortInstitutionSetCache();
//	}
        

	
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
                        $pid=getCurrentPermission(getUser('admin'))['pid'];//得到当前父级pid
                        $curr_institution= \App\Models\Institution::find($pid)->toArray();//得到父级机构对象
			$institutionList = $this->sortInstitution($institutions,$pid);//得到父级机构对象 及所有子级
                        //dd($curr_institution,$institutionList);
                        $arr=[];
                        
                        //$arr[0]['child'] = $institutionList;
                        //dd($curr_institution,$institutionList,$arr);
                        //dd($arr);
                        $curr_institution['child'] = $institutionList; 
                        $arr[] = $curr_institution;
                        //dd($arr);
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
                        //dd($institutionList);
			// 缓存菜单数据
			cache()->forever('institutionList',$institutionList);
			return $institutionList;
			
		}
		return '';
	}
        
        

}
