<?php
namespace App\Repositories\Presenters;

class EmployeePresenter {
	
	public function sexList($sexId=0)
	{
		$html = '';

						$html .= <<<Eof
		        <div class="col-md-1">
	                     	<div class="i-checks">
	                        	<label> <input type="radio" name="sex" {$this->checkSex(0,$sexId)} value="0"> <i></i>女</label>
	                      	</div>
                      	</div>
                        <div class="col-md-1">
	                     	<div class="i-checks">
	                        	<label> <input type="radio" name="sex" {$this->checkSex(1,$sexId)} value="1"> <i></i>男</label>
	                      	</div>
                      	</div>
Eof;

				
			
		
		return $html;
	}
        
        
        private function checkSex($id,$sexId)
	{
		
		if ($id == $sexId) {

				return  'checked="checked"';

		}
		return '';
	}
        
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
                                     //   $html .=self::topInstitutionList($v['child'],$pid);
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
}