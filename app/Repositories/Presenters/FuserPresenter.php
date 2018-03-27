<?php
namespace App\Repositories\Presenters;

class FuserPresenter {
	
        //$pid 入口id
	public function topInstitutionList($institutions,$pid=0)
	{
            //dd($institutions);
		//$html = '<option value="0">顶级机构</option>';
                $html='';
                //if($isClear){ //是否第一次
                //    $html = '<option value="0">顶级机构</option>';
                //}
		if ($institutions) {
                    foreach ($institutions as $k=>$v) {
          
                                    $html .= '<option value="'.$k.'" '.$this->checkInstitution($k,$pid).'>'.str_repeat("&nbsp;&nbsp;",$v['level']).$v['name'].'</option>';
			        
                                    //if($v['child']){
                                    //    $html .=self::topInstitutionList($v['child'],$pid,$level+$pid);
                                    //}
                                
                                
                        }
                        
		}
		return $html;
	}
	public function checkInstitution($institutionId,$pid)
	{
		//if ($pid !== 0) {
			if (intval($institutionId) == intval($pid)) {
				return 'selected="selected"';
                               // \dump('true');
			}
			return '';
		//}
		//return '';
	}
        
        //$pid 入口id
	public function topEmployeeList($employees,$pid=0)
	{
            //dd($institutions);
		//$html = '<option value="0">顶级机构</option>';
                $html='';
                //if($isClear){ //是否第一次
                //    $html = '<option value="0">顶级机构</option>';
                //}
		if ($employees) {
                    foreach ($employees as $k=>$v) {
          
                                    $html .= '<option value="'.$k.'" '.$this->checkEmployee($k,$pid).'>'.$v.'</option>';
			        
                                    //if($v['child']){
                                    //    $html .=self::topInstitutionList($v['child'],$pid,$level+$pid);
                                    //}
                                
                                
                        }
                        
		}
		return $html;
	}
	public function checkEmployee($employeeId,$id)
	{
		//if ($pid !== 0) {
			if (intval($employeeId) == intval($id)) {
				return 'selected="selected"';
                               // \dump('true');
			}
			return '';
		//}
		//return '';
	}
}