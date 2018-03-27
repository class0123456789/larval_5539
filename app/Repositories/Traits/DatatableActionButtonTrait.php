<?php
namespace App\Repositories\Traits;
use Illuminate\Support\Facades\Gate;


trait DatatableActionButtonTrait {
/**
	 * 修改按钮
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $id [description]
	 * @return [type]         [description]
	 */
	private function getEditActionButton($id)
	{
		if (Gate::forUser(auth('admin')->user())->check('admin.'.$this->module.'.edit')) {
			$url = route('admin.'.$this->module.'.edit', $id);
			$edit = '修改';
			return <<<Eof
				<a href="{$url}" class="btn btn-xs btn-outline btn-warning tooltips" data-original-title="{$edit}" data-placement="top">
					<i class="fa fa-edit"></i>
				</a>
Eof;
		}
		return '';
	}
	/**
	 * 删除按钮
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $id [description]
	 * @return [type]         [description]
	 */
	private function getDestroyActionButton($id)
	{
		if (Gate::forUser(auth('admin')->user())->check('admin.'.$this->module.'.destroy')) {
			$url = route('admin.'.$this->module.'.destroy', $id);
			$delete = '删除';
			$csrfToken = csrf_field();
			$method = method_field('delete');
			return <<<Eof
				<a href="javascript:;" onclick="return false" class="btn btn-xs btn-outline btn-danger tooltips destroy_item" data-original-title="{$delete}"  data-placement="top">
					<i class="fa fa-trash"></i>
					<form action="{$url}" method="POST" style="display:none">
						$csrfToken
						$method
					</form>
				</a>
Eof;
		}
		return '';
	}
	/**
	 * 模态框查看按钮
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $id [description]
	 * @return [type]         [description]
	 */
	private function getModalShowActionButtion($id)
	{
		if (Gate::forUser(auth('admin')->user())->check('admin.'.$this->module.'.show')) {
			return '<a href="'.route('admin.'.$this->module.'.show', $id).'" class="btn btn-xs btn-info tooltips" data-toggle="modal" data-target="#myModal" data-original-title="查看"  data-placement="top"><i class="fa fa-eye"></i></a> ';
		}
		return '';
	}
	/**
	 * 超链接查看按钮
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $id [description]
	 * @return [type]         [description]
	 */
	private function getShowActionButtion($id)
	{
		if (Gate::forUser(auth('admin')->user())->check('admin.'.$this->module.'.show')) {
			return '<a href="'.route('admin.'.$this->module.'.show', $id).'" class="btn btn-xs btn-info tooltips" data-original-title="查看"  data-placement="top"><i class="fa fa-eye"></i></a> ';
		}
		return '';
	}
	/**
	 * 默认显示编辑和删除，如果需要查看再相应service进行重写
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $id [description]
	 * @return [type]         [description]
	 */
	public function getActionButtonAttribute($id)
	{
		return $this->getEditActionButton($id)
				.$this->getDestroyActionButton($id);
	}
    
}