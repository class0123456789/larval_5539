<?php
namespace App\Repositories\Presenters;

class MenuPresenter {
	
	public function menuNestable($menus)
	{
		if ($menus) {
			$item = '';
			foreach ($menus as $v) {
				$item.= $this->getNestableItem($v);
			}
			return $item;
		}
		return '暂无菜单';
	}
	
	/**
	 * 返回菜单HTML代码
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $menu [description]
	 * @return [type]           [description]
	 */
	protected function getNestableItem($menu)
	{
		$icon = $menu['icon'] ? '<i class="'.$menu['icon'].'"></i>' : '';
		if ($menu['child']) {
			return $this->getHandleList($menu['id'],$menu['name'],$icon,$menu['child']);
		}
		$labelInfo = $menu['pid'] == 0 ?  'label-info':'label-warning';
		return <<<Eof
				<li class="dd-item dd3-item" data-id="{$menu['id']}">
                    <div class="dd-handle dd3-handle">Drag</div>
                    <div class="dd3-content"><span class="label {$labelInfo}">{$icon}</span> {$menu['name']} {$this->getActionButtons($menu['id'])}</div>
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
	 * 菜单按钮
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $id [description]
	 * @return [type]         [description]
	 */
	protected function getActionButtons($id)
	{
		$action = '<div class="pull-right">';
		//$encodeId =  [encodeId($id, 'menu')];
		if (haspermission('menu.show')) {
			$action .= '<a href="javascript:;" class="btn btn-xs tooltips showInfo" data-href="'.route('menu.show',  $id).'" data-toggle="tooltip" data-original-title="查看"  data-placement="top"><i class="fa fa-eye"></i></a>';
		}
		if (haspermission('menu.edit')) {
			$action .= '<a href="javascript:;" data-href="'.route('menu.edit', $id).'" class="btn btn-xs tooltips editMenu" data-toggle="tooltip" data-original-title="修改"  data-placement="top"><i class="fa fa-edit"></i></a>';
		}
		if (haspermission('menu.destroy')) {
			$action .= '<a href="javascript:;" class="btn btn-xs tooltips destroy_item" data-id="'.$id.'" data-original-title="删除"data-toggle="tooltip"  data-placement="top"><i class="fa fa-trash"></i><form action="'.route('menu.destroy',  $id).'" method="POST" style="display:none"><input type="hidden"name="_method" value="delete"><input type="hidden" name="_token" value="'.csrf_token().'"></form></a>';
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
	public function canCreateMenu()
	{
		$canCreateMenu = haspermission('menu.create');
		$title = $canCreateMenu ?  '欢迎！':'对不起！';
		$desc = $canCreateMenu ? '你可以增加彩单在左边':'你没有权限去增加菜单项';
		$createButton = $canCreateMenu ? '<br><a href="javascript:;" class="btn btn-primary m-t create_menu">增加菜单</a>':'';
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
	 * 添加修改菜单关系select
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $menus [description]
	 * @param  string     $pid   [description]
	 * @return [type]            [description]
	 */
	public function topMenuList($menus,$pid = '')
	{
		$html = '<option value="0">顶级菜单</option>';
		if ($menus) {
			foreach ($menus as $v) {
				$html .= '<option value="'.$v['id'].'" '.$this->checkMenu($v['id'],$pid).'>'.$v['name'].'</option>';
			}
		}
		return $html;
	}
	public function checkMenu($menuId,$pid)
	{
		if ($pid !== '') {
			if ($menuId == $pid) {
				return 'selected="selected"';
			}
			return '';
		}
		return '';
	}
	
	/**
	 * 获取菜单关系名称
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $menus [description]
	 * @param  [type]     $pid   [description]
	 * @return [type]            [description]
	 */
	public function topMenuName($menus,$pid)
	{
		if ($pid == 0) {
			return '顶级菜单';
		}
		if ($menus) {
			foreach ($menus as $v) {
				if ($v['id'] == $pid) {
					return $v['name'];
				}
			}
		}
		return '';
	}
	
	/**
	 * 后台左侧菜单
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $sidebarMenus [description]
	 * @return [type]                   [description]
	 */
	public function sidebarMenuList($sidebarMenus)
	{
		$html = '';
		if ($sidebarMenus) {
			foreach ($sidebarMenus as $menu) {

			    //权限为true,url为空时,可以通过  或者 url 存在 在路由表中 且 权限为true 时 也可以通过
                if ((haspermission($menu['slug']) && !$menu['url']) || (isset(\Route::getRoutes()->getRoutesByMethod()['GET'][$menu['url']]) && haspermission($menu['slug']))) {//一级菜单的处理
                    //continue;
                    if (!$menu['url']) {//不存在时直接附给slug
                        $menu['url'] = $menu['slug'];
                    }
                    if ($menu['child']) {//子级菜单的处理


                        $url = url($menu['url']);
                        $active = active_class(if_uri_pattern(explode(',', $menu['active'])), 'active');
                        //dd($url);
                        $html .= <<<Eof
					<li class="{$active}">
			          	<a href="{$url}"><i class="fa {$menu['icon']}"></i> <span class="nav-label">{$menu['name']}</span> <span class="fa arrow"></span></a>
			          	<ul class="nav nav-second-level collapse">
			              	{$this->childMenu($menu['child'])}
			          	</ul>
			      	</li>
Eof;
                    }else {
                        $html .= '<li class="' . active_class(if_uri_pattern(explode(',', $menu['active'])), 'active') . '"><a href="' . url($menu['url']) . '"><i class="fa ' . $menu['icon'] . '"></i> <span class="nav-label">' . $menu['name'] . '</span></a></li>';
                    }
                }
            }
		}
		return $html;
	}
	
	/**
	 * 多级菜单显示
	 * @author 晚黎
	 * @date   2017-11-06
	 * @param  [type]     $childMenu [description]
	 * @return [type]                [description]
	 */
	public function childMenu($childMenu)
	{
		$html = '';
		if ($childMenu) {
			foreach ($childMenu as $v) {
                //权限为true,url为空时,可以通过  或者 url 存在 在路由表中 且 权限为true 时 也可以通过
                if ((haspermission($v['slug']) && !$v['url']) || (isset(\Route::getRoutes()->getRoutesByMethod()['GET'][$v['url']]) && haspermission($v['slug']))){
                        if (!$v['url']) {//不存在时直接附给slug
                            $v['url'] = $v['slug'];
                        }
			    //已在get路由中存在且操作权限还是真 才显是子菜单
			    //if(isset(\Route::getRoutes()->getRoutesByMethod()['GET'][$v['url']]) && haspermission($v['slug']) ){
				    $icon = $v['icon'] ? '<i class="fa '.$v['icon'].'"></i>':'';
				    $html .= '<li class="'.active_class(if_uri_pattern(explode(',',$v['active'])),'active').'"><a href="'.url($v['url']).'">'.$icon.$v['name'].'</a></li>';
                }
            }
		}
		return $html;
	}
	public function permissionList($permissionss, $slug = '')
	{
		$str = '';
		//dump($permissions);
		//if ($permissions->isNotEmpty()) {
        if ($permissionss) {
           foreach ($permissionss as $permissions){
               foreach ($permissions as $k=>$v) {

                   $str .= "<option value='{$v['slug']}' {$this->checkMenu($v['slug'],$slug)}>{$v['name']}</option>";
               }
           }

		}
		return $str;
	}
}