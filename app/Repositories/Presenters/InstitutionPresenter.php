<?php
namespace App\Repositories\Presenters;

class InstitutionPresenter {
	public function institutionNestable($institutions)
	{
            //dd($institutions);
		if ($institutions) {
			$item = '';
			foreach ($institutions as $v) {
				$item.= $this->getNestableItem($v);
			}
			return $item;
		}
		return '暂无机构';
	}
	
	/**
	 * 返回机构HTML代码
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $institution [description]
	 * @return [type]           [description]
	 */
	protected function getNestableItem($institution)
	{
		$icon = '<i class="fa-dashboard"></i>' ;
		if ($institution['child']) {
			return $this->getHandleList($institution['id'],$institution['name'],$icon,$institution['child']);
		}
		$labelInfo = $institution['parent_id'] == 0 ?  'label-info':'label-warning';
		return <<<Eof
				<li class="dd-item dd3-item" data-id="{$institution['id']}">
                    <div class="dd-handle dd3-handle">Drag</div>
                    <div class="dd3-content"><span class="label {$labelInfo}">{$icon}</span> {$institution['name']} {$this->getActionButtons($institution['id'])}</div>
                </li>
Eof;
	}
	
	/**
	 * 判断是否有子集
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $id    [description]
	 * @param  [type]     $name  [description]
	 * @param  [type]     $icon  [description]
	 * @param  [type]     $child [description]
	 * @return [type]            [description]
	 */
	protected function getHandleList($id,$name,$icon,$child)
	{
		$handle = '';
		if ($child) {
			foreach ($child as $v) {
				$handle .= $this->getNestableItem($v);
			}
		}
		$html = <<<Eof
		<li class="dd-item dd3-item" data-id="{$id}">
            <div class="dd-handle dd3-handle">Drag</div>
            <div class="dd3-content">
            	<span class="label label-info">{$icon}</span> {$name} {$this->getActionButtons($id)}
            </div>
            <ol class="dd-list">
                {$handle}
            </ol>
        </li>
Eof;
		return $html;
	}
	
	/**
	 * 机构按钮
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $id [description]
	 * @return [type]         [description]
	 */
	protected function getActionButtons($id)
	{
                $pid=getCurrentPermission(getUser('admin'))['pid'];
		$action = '<div class="pull-right">';
		//$encodeId =  [encodeId($id, 'institution')];
		if (haspermission('institution.show') && $id!=$pid) {
			$action .= '<a href="javascript:;" class="btn btn-xs tooltips showInfo" data-href="'.route('institution.show',  $id).'" data-toggle="tooltip" data-original-title="查看"  data-placement="top"><i class="fa fa-eye"></i></a>';
		}
		if (haspermission('institution.edit') && $id!=$pid) {
			$action .= '<a href="javascript:;" data-href="'.route('institution.edit', $id).'" class="btn btn-xs tooltips editInstitution" data-toggle="tooltip" data-original-title="修改"  data-placement="top"><i class="fa fa-edit"></i></a>';
		}
		if (haspermission('institution.destroy') && $id!=$pid) {
			$action .= '<a href="javascript:;" class="btn btn-xs tooltips destroy_item" data-id="'.$id.'" data-original-title="删除"data-toggle="tooltip"  data-placement="top"><i class="fa fa-trash"></i><form action="'.route('institution.destroy',  $id).'" method="POST" style="display:none"><input type="hidden"name="_method" value="delete"><input type="hidden" name="_token" value="'.csrf_token().'"></form></a>';
		}
		$action .= '</div>';
		return $action;
	}

	/**
	 * 根据用户不同的权限显示不同的内容
	 * @author 晚黎
	 * @date   2017-11-06
	 * @return [type]     [description]
	 */
	public function canCreateInstitution()
	{
		$canCreateInstitution = haspermission('institution.create');
		$title = $canCreateInstitution ?  '欢迎！':'对不起！';
		$desc = $canCreateInstitution ? '你可以增加彩单在左边':'你没有权限去增加机构项';
		$createButton = $canCreateInstitution ? '<br><a href="javascript:;" class="btn btn-primary m-t create_institution">增加机构</a>':'';
		return <<<Eof
		<div class="middle-box text-center animated fadeInRightBig">
            <h3 class="font-bold">{$title}</h3>
            <div class="error-desc">
                {$desc}{$createButton}
            </div>
        </div>
Eof;
	}
	
	/**
	 * 添加修改机构关系select
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $institutions [description]
	 * @param  string     $pid   [description]
	 * @return [type]            [description]
	 */
        //$pid 是设置默认选中id值
	public function topInstitutionList($institutions,$pid=0)
	{
            //dd($institutions);
		//$html = '<option value="0">顶级机构</option>';
                $html='';
                //if($isClear){ //是否第一次
                //    $html = '<option value="0">顶级机构</option>';
                //}
		if ($institutions) {
                    foreach ($institutions as $v) {
          
                                    $html .= '<option value="'.$v['id'].'" '.$this->checkInstitution($v['id'],$pid).'>'.str_repeat("&nbsp;&nbsp;",$v['level']).$v['name'].'</option>';
			        
                                    if($v['child']){
                                        $html .=self::topInstitutionList($v['child'],$pid,$v['level']);
                                    }
                                
                                
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
	
	/**
	 * 获取机构关系名称
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $institutions [description]
	 * @param  [type]     $pid   [description]
	 * @return [type]            [description]
	 */
	public function topInstitutionName($institutions,$pid)
	{
		//if ($pid == 0) {
		//	return '顶级机构';
		//}
		if ($institutions) {
			foreach ($institutions as $v) {
				if ($v['id'] == $pid) {
					return $v['name'];
				}
			}
		}
		return '';
	}
	
	



	public function kindList($kinds, $kid = 0)
	{
		$str = '';
		if ($kinds->isNotEmpty()) {
			foreach ($kinds as $v) {
				$str .= "<option value='{$v->id}' {$this->checkInstitution($v->id,$kid)}>{$v->name}</option>";
			}
		}
		return $str;
	}
        
        //返回机构类型名
	public function topKindName($kinds,$kid)
	{

		if ($kinds) {
			foreach ($kinds as $v) {
				if ($v['id'] == $kid) {
					return $v['name'];
				}
			}
		}
		return '';
	}
	
}