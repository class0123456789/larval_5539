<?php
namespace App\Repositories\Presenters;

class DeviceModelPresenter {
    //$pid 入口id
    public function topDeviceClasslList($curr_deviceclasses,$pid=0)
    {
        //dd($institutions);
        //$html = '<option value="0">顶级机构</option>';
        $html='';
        //if($isClear){ //是否第一次
        //    $html = '<option value="0">顶级机构</option>';
        //}
        if ($curr_deviceclasses) {
            foreach ($curr_deviceclasses as $k=>$v) {

                $html .= '<option value="'.$k.'" '.$this->checkInstitution($k,$pid). ' haschild="'.$v['haschild'].'" >'.str_repeat("&nbsp;&nbsp;",$v['level']).$v['name'].'</option>';

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
    public function topBrandList($brands,$pid=0)
    {
        //dd($institutions);
        //$html = '<option value="0">顶级机构</option>';
        $html='';
        //if($isClear){ //是否第一次
        //    $html = '<option value="0">顶级机构</option>';
        //}
        if ($brands) {
            foreach ($brands as $k=>$v) {

                $html .= '<option value="'.$k.'" '.$this->checkBrand($k,$pid).'>'.$v.'</option>';

                //if($v['child']){
                //    $html .=self::topInstitutionList($v['child'],$pid,$level+$pid);
                //}


            }

        }
        return $html;
    }
    public function checkBrand($employeeId,$id)
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