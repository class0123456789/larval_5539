<?php
namespace App\Repositories\Presenters;

class DeviceClassPresenter {
    public function deviceclassNestable($deviceclasses)
    {
        //dd($deviceclasses);
        if ($deviceclasses) {
            $item = '';
            foreach ($deviceclasses as $v) {
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
    protected function getNestableItem($deviceclass)
    {
        $icon = '<i class="fa-dashboard"></i>' ;
        if ($deviceclass['child']) {
            return $this->getHandleList($deviceclass['id'],$deviceclass['name'],$icon,$deviceclass['child']);
        }
        $labelInfo = $deviceclass['parent_id'] == 0 ?  'label-info':'label-warning';
        return <<<Eof
				<li class="dd-item dd3-item" data-id="{$deviceclass['id']}">
                    <div class="dd-handle dd3-handle">Drag</div>
                    <div class="dd3-content"><span class="label {$labelInfo}">{$icon}</span> {$deviceclass['name']} {$this->getActionButtons($deviceclass['id'])}</div>
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
        //$pid=getCurrentPermission(getUser('admin'))['pid'];
        $action = '<div class="pull-right">';
        //$encodeId =  [encodeId($id, 'institution')];
        if (haspermission('deviceclass.show') &&  $id !=1) {
            $action .= '<a href="javascript:;" class="btn btn-xs tooltips showInfo" data-href="'.route('deviceclass.show',  $id).'" data-toggle="tooltip" data-original-title="查看"  data-placement="top"><i class="fa fa-eye"></i></a>';
        }
        if (haspermission('deviceclass.edit') &&  $id !=1) {
            $action .= '<a href="javascript:;" data-href="'.route('deviceclass.edit', $id).'" class="btn btn-xs tooltips editDeviceClass" data-toggle="tooltip" data-original-title="修改"  data-placement="top"><i class="fa fa-edit"></i></a>';
        }
        if (haspermission('deviceclass.destroy')  && $id !=1) {
            $action .= '<a href="javascript:;" class="btn btn-xs tooltips destroy_item" data-id="'.$id.'" data-original-title="删除"data-toggle="tooltip"  data-placement="top"><i class="fa fa-trash"></i><form action="'.route('deviceclass.destroy',  $id).'" method="POST" style="display:none"><input type="hidden"name="_method" value="delete"><input type="hidden" name="_token" value="'.csrf_token().'"></form></a>';
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
    public function canCreatDeviceClass()
    {
        $canCreatedeviceclass = haspermission('deviceclass.create');
        $title = $canCreatedeviceclass ?  '欢迎！':'对不起！';
        $desc = $canCreatedeviceclass ? '你可以增加彩单在左边':'你没有权限去增加设备分类项';
        $createButton = $canCreatedeviceclass ? '<br><a href="javascript:;" class="btn btn-primary m-t create_DeviceClass">增加设备分类</a>':'';
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
     * @param  [type]     $deviceclasses [description]
     * @param  string     $pid   [description]
     * @return [type]            [description]
     */
    //$pid 是设置默认选中id值
    public function topDeviceClassList($deviceclasses,$pid=0)
    {
        //dd($deviceclasses);
        //$html = '<option value="0">顶级机构</option>';
        $html='';
        //if($isClear){ //是否第一次
        //    $html = '<option value="0">顶级机构</option>';
        //}
        if ($deviceclasses) {
            foreach ($deviceclasses as $v) {

                $html .= '<option value="'.$v['id'].'" '.$this->checkDeviceClass($v['id'],$pid).'>'.str_repeat("&nbsp;&nbsp;",$v['level']).$v['name'].'</option>';

                if($v['child']){
                    $html .=self::topDeviceClassList($v['child'],$pid,$v['level']);
                }


            }

        }
        return $html;
    }
    public function checkDeviceClass($deviceclassId,$pid)
    {
        //if ($pid !== 0) {
        if (intval($deviceclassId) == intval($pid)) {
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
     * @param  [type]     $deviceclasses [description]
     * @param  [type]     $pid   [description]
     * @return [type]            [description]
     */
    public function topDeviceClassName($deviceclasses,$pid)
    {
        //if ($pid == 0) {
        //	return '顶级机构';
        //}
        if ($deviceclasses) {
            foreach ($deviceclasses as $k=>$v) {
                if ($k == $pid) {
                    return $v['name'];
                }
            }
        }
        return '';
    }




	
}