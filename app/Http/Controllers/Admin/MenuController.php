<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MenuRequest;
use App\Models\Menu;
use App\Models\Permission;
use App\Http\Controllers\Controller;
use Cache;
use Exception;


class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$menus = $this->getMenuList();
        $menus = getMenuList();
        return view('admin.menu.index')->with(compact('menus'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd(1111);
        //$result = $this->service->create();
        //$menus =$this->getMenuList();
        $menus = getMenuList();
	    //$permissions = Permission::all(['name', 'slug']);
        $permissions = getAllPermissions();
        //dump($permissions);
        // dd($menus);
		//return compact('menus', 'permissions');
        return view('admin.menu.create')->with(compact('menus', 'permissions'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenuRequest $request)
    {
        //dd('11111');
        
        //dd($attributes);
		try {
			$result = Menu::create($request->all());
                        //dump($result);
			if ($result) {
				// 更新缓存
				//$this->sortMenuSetCache();
                SetMenuCache();
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
			//$menus = $this->getMenuList();
            $menus = getMenuList();
			$menu = Menu::find($id);
                        return view('admin.menu.show')->with(compact('menus', 'menu'));
			//return compact('menus', 'menu');
		} catch (Exception $e) {
			//abort(500);
                        flash($e->getMessage(), 'danger');
                        redirect('/admin/menu');
	}
        //return view('admin.menu.show')->with($result);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$result = $this->service->edit($id);
        
        try {
			//$attr = $this->show($id);
                        //$menus = $this->getMenuList();
            $menus = getMenuList();
			$menu = Menu::find($id);
			//$permissions = Permission::all(['name', 'slug']);
            $permissions = getAllPermissions();
			//return array_merge($attr, compact('permissions'));
                          return view('admin.menu.edit')->with(compact('permissions', 'menus','menu'));
            } catch (Exception $e) {
			//abort(500);
                flash($e->getMessage(), 'danger');
                redirect('/admin/menu');
	}
       // return view('admin.menu.edit')->with($result);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MenuRequest $request, $id)
    {
               try {    
                        unset($request['_token']);
                        unset($request['_method']);
			$isUpdate = Menu::where('id',$id)->update($request->all());
			if ($isUpdate) {
				// 更新缓存
				//$this->sortMenuSetCache();
                SetMenuCache();
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
			$result = Menu::destroy($id);
                        //$result = Menu::delete($id);
                        //dd($result);
			if ($result) {
				//$this->sortMenuSetCache();
                SetMenuCache();
			}
			flash_info($result,'删除成功','删除失败');
		} catch (Exception $e) {
                        //dd($e->getMessage());
			flash($e->getMessage(), 'danger');
		}
        //$this->service->destroy($id);
        return redirect()->route('menu.index');
    }

    
   
    
     /**
     * 清除菜单缓存
     * @author 晚黎
     * @date   2017-11-07
     * @return [type]     [description]
     */

    public function cacheClear()
    {
	         //cache()->forget('menuList');
              cacheClear('menuList');
                //flash_info($result,'权限删除成功','权限删除失败');
		      flash('菜单清理成功', 'success');
                 return redirect()->route('menu.index');
    }
    

        

}
