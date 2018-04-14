<?php
namespace App\Repositories\Presenters;

class DeviceWareHousePresenter {
    //传入设备brand
    public function topDeviceBrandList($devicebrands,$brand_id=0)
    {
        //dd($institutions);
        //$html = '<option value="0">顶级机构</option>';
        $html='';
        //if($isClear){ //是否第一次
        //    $html = '<option value="0">顶级机构</option>';
        //}
        if ($devicebrands) {
            foreach ($devicebrands as $k=>$v) {

                $html .= '<option value="'.$v['id'].'" '.$this->checkOption($v['id'],$brand_id).'>'.$v['name'].'</option>';

                //if($v['child']){
                //    $html .=self::topInstitutionList($v['child'],$pid,$level+$pid);
                //}


            }

        }
        return $html;
    }

    //传入设备分类
    public function topDeviceClassList($deviceclasses ,$class_id=0)
    {
        //dd($institutions);
        //$html = '<option value="0">顶级机构</option>';
        $html='';
        //if($isClear){ //是否第一次
        //    $html = '<option value="0">顶级机构</option>';
        //}
        if ($deviceclasses) {
            foreach ($deviceclasses as $k=>$v) {

                $html .= '<option value="'.$k.'" '.$this->checkOption($k,$class_id).'>'.str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;",$v['level']).$v['name'].'</option>';

                //if($v['child']){
                //    $html .=self::topInstitutionList($v['child'],$pid,$level+$pid);
                //}


            }

        }
        return $html;
    }
	//传入设备model
    public function topDeviceModelList($devicemodels,$devicemodel_id=0)
    {
        //dd($institutions);
        //$html = '<option value="0">顶级机构</option>';
        $html='';
        //if($isClear){ //是否第一次
        //    $html = '<option value="0">顶级机构</option>';
        //}
        if ($devicemodels) {
            foreach ($devicemodels as $k=>$v) {

                $html .= '<option value="'.$v['id'].'" '.$this->checkOption($v['id'],$devicemodel_id).'>'.$v['name'].'</option>';

                //if($v['child']){
                //    $html .=self::topInstitutionList($v['child'],$pid,$level+$pid);
                //}


            }

        }
        return $html;
    }
    //传入设备审批文件
    public function topDeviceFinancialapprovalList($financialapprovals,$financialapproval_id=0)
    {
        //dd($institutions);
        //$html = '<option value="0">顶级机构</option>';
        $html='';
        //if($isClear){ //是否第一次
        //    $html = '<option value="0">顶级机构</option>';
        //}
        if ($financialapprovals) {
            foreach ($financialapprovals as $k=>$v) {

                $html .= '<option value="'.$v['id'].'" '.$this->checkOption($v['id'],$financialapproval_id).'>'.$v['file_no'].'</option>';

                //if($v['child']){
                //    $html .=self::topInstitutionList($v['child'],$pid,$level+$pid);
                //}


            }

        }
        return $html;
    }
    //传入供应商
    public function topDeviceSupplierList($devicesuppliers,$supplier_id=0)
    {
        //dd($institutions);
        //$html = '<option value="0">顶级机构</option>';
        $html='';
        //if($isClear){ //是否第一次
        //    $html = '<option value="0">顶级机构</option>';
        //}
        if ($devicesuppliers) {
            foreach ($devicesuppliers as $k=>$v) {

                $html .= '<option value="'.$v['id'].'" '.$this->checkOption($v['id'],$supplier_id).'>'.$v['name'].'</option>';

                //if($v['child']){
                //    $html .=self::topInstitutionList($v['child'],$pid,$level+$pid);
                //}


            }

        }
        return $html;
    }
    //传入维保厂商
    public function topMaintenanceProviderList($maintenanceproviders,$maintenanceprovider_id=0)
    {
        //dd($institutions);
        //$html = '<option value="0">顶级机构</option>';
        $html='';
        //if($isClear){ //是否第一次
        //    $html = '<option value="0">顶级机构</option>';
        //}
        if ($maintenanceproviders) {
            foreach ($maintenanceproviders as $k=>$v) {

                $html .= '<option value="'.$v['id'].'" '.$this->checkOption($v['id'],$maintenanceprovider_id).'>'.$v['name'].'</option>';

                //if($v['child']){
                //    $html .=self::topInstitutionList($v['child'],$pid,$level+$pid);
                //}


            }

        }
        return $html;
    }
    //财务审批

    //设备用途
    public function topDeviceEquipmentUseList($equipmentuses,$equipmentuse_id=0)
    {
        //dd($institutions);
        //$html = '<option value="0">顶级机构</option>';
        $html='';
        //if($isClear){ //是否第一次
        //    $html = '<option value="0">顶级机构</option>';
        //}
        if ($equipmentuses) {
            foreach ($equipmentuses as $k=>$v) {

                $html .= '<option value="'.$v['id'].'" '.$this->checkOption($v['id'],$equipmentuse_id).'>'.$v['name'].'</option>';

                //if($v['child']){
                //    $html .=self::topInstitutionList($v['child'],$pid,$level+$pid);
                //}


            }

        }
        return $html;
    }
    //设备工作状态
    //设备流通状态

    //设备所在机构 新增0
    //使用人 登记人
    public function checkOption($selectId,$curr_id)
    {
        //if ($pid !== 0) {
        if (intval($selectId) == intval($curr_id)) {
            return 'selected="selected"';
            // \dump('true');
        }
        return '';
        //}
        //return '';
    }
}