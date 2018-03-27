<?php
namespace App\Services;

use Facades\ {
    App\Repositories\Eloquent\PermissionRepository,
    Yajra\DataTables\Html\Builder
};
use App\Repositories\Traits\DatatableActionButtonTrait;

use DataTables;
use Exception;

class PermissionService {
    use DatatableActionButtonTrait;

	protected $module = 'permission';
	protected $indexRoute = 'permission.index';
	protected $createRoute = 'permission.create';
	protected $editRoute = 'permission.edit';
	protected $destroyRoute = 'permission.destroy';
	
	public function __construct()
	{
		
	}
        /**
	 * 权限首页
	 * @author 晚黎
	 * @date   2017-11-06
	 * @return [type]     [description]
	 */
	public function index()
	{
		if (request()->ajax()) {
			return $this->ajaxData();
		}

		$html = Builder::parameters([
                                
				'searchDelay' => 350,
			    'language' => [
			        'url' => url('/vendors/dataTables/language/zh.json')
			    ],
                    
                     "serverSide" => true,
                     //"bProcessing"=> true,
                    "ajax"=>[
                        'url'=>'/admin/permission/index',
                        'type'=>'get',
                    ],    

                                

		    'drawCallback' => <<<Eof
					function() {
				        LaravelDataTables["dataTableBuilder"].$('.tooltips').tooltip( {
				          placement : 'top',
				          html : true
				        });
                                       // let htmlable="<label>角色<select name='jgh' id='jgh'><option value='0'>所有</option><option value='1'>列表</option><option value='2'>超级管理员</option></select></label>";
      //$("#dataTableBuilder_length").prepend(htmlable);
                   // $('#jgh').change(function () {
                                   // LaravelDataTables["dataTableBuilder"].context[0]['oAjaxData']['jgh'] = $('#jgh').val();
                    //console.log(window.LaravelDataTables["dataTableBuilder"].context[0]['oAjaxData']['jgh']);
                     //   });
			        },
Eof
			])->addIndex(['data' => 'DT_Row_Index', 'name' => 'DT_Row_Index', 'title' => '序号'])
			->addColumn(['data' => 'name', 'name' => 'name', 'title' => '权限'])
	        ->addColumn(['data' => 'label', 'name' => 'label', 'title' => '权限名称'])
	        ->addColumn(['data' => 'description', 'name' => 'description', 'title' => '描述'])
	        ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => '增加时间'])
	        ->addColumn(['data' => 'updated_at', 'name' => 'updated_at', 'title' => '修改时间'])
	        ->addAction(['data' => 'action', 'name' => 'action', 'title' => '操作']);
        return compact('html');
	}
	
	/**
	 * datatable数据
	 * @author 晚黎
	 * @date   2017-11-06
	 * @return [type]     [description]
	 */
	public function ajaxData()
	{
            
		return DataTables::of(PermissionRepository::all())
			->addIndexColumn()
			->addColumn('action', function ($permission)
			{
				return $this->getActionButtonAttribute($permission->id);
			})
			->make(true);
	}
	/**
	 * 添加权限
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $attributes [description]
	 * @return [type]                 [description]
	 */
	public function store($attributes)
	{
		try {
			$result = PermissionRepository::create($attributes);
			flash_info($result,'增加成功','增加失败');
			return isset($attributes['rediret']) ? $this->createRoute : $this->indexRoute;
		} catch (Exception $e) {
                        flash('增加错误', 'danger');
			return $this->createRoute;
		}
	}
	/**
	 * 修改权限
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $id [description]
	 * @return [type]         [description]
	 */
	public function edit($id)
	{
		try {
			$permission = PermissionRepository::find($id);
			return compact('permission');
		} catch (Exception $e) {
			flash('查找错误', 'danger');
			return redirect()->route($this->indexRoute);
		}
	}
	/**
	 * 修改数据
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $attributes [description]
	 * @param  [type]     $id         [description]
	 * @return [type]                 [description]
	 */
	public function update($attributes, $id)
	{
		try {
			$result = PermissionRepository::update($attributes, decodeId($id, $this->module));
			flash_info($result,'修改成功','修改失败');
			return $this->indexRoute;
		} catch (Exception $e) {
			flash('修改错误', 'danger');
			return $this->indexRoute;
		}
	}
	/**
	 * 删除数据
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $id [description]
	 * @return [type]         [description]
	 */
	public function destroy($id)
	{
		try {
			$result = PermissionRepository::delete($id);
			flash_info($result,'删除成功','删除失败');
		} catch (Exception $e) {
			flash('删除错误', 'danger');
		}
	}
	
}