<?php

namespace App\Http\Middleware;

use Closure;
use Auth, Cache;

class GetMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->attributes->set('comData_menu', $this->getMenu());

        return $next($request);
    }

    /**
     * 获取左边菜单栏
     * @return array
     */
    function getMenu()
    {
        $openArr = [];
        $data = [];
        $data['top'] = [];
        //查找并拼接出地址的别名值
        $path_arr = explode('/', \URL::getRequest()->path());
        //dd($path_arr);
        if (isset($path_arr[1])) {
            $urlPath = $path_arr[0] . '.' . $path_arr[1] . '.index';
        } else {
            $urlPath = $path_arr[0] . '.index';
        }
        //查找出所有的地址
        $table = Cache::store('file')->rememberForever('menus', function () {
            return \App\Models\Permission::where('name', 'LIKE', '%index')
                ->orWhere('cid', 1) //一级菜单
                ->get();
        });
        foreach ($table as $v) {//一级菜单
            if ($v->cid == 1 || \Gate::forUser(auth('admin')->user())->check($v->name)) {
                if ($v->name == $urlPath) {
                    $openArr[] = $v->id;
                    $openArr[] = $v->cid;
                }
                $data[$v->cid][] = $v->toarray();
            }
        }
        dd($data);
        foreach ($data[1] as $v) {
            if (isset($data[$v['id']]) && is_array($data[$v['id']]) && count($data[$v['id']]) > 0) {
            //if (is_array($data[$v['id']]) ) {
                $data['top'][] = $v;
            }
        }
        //dd($data);
        unset($data[0]);
        //ation open 可以在函数中计算给他
        $data['openarr'] = array_unique($openArr);
        return $data;
    }
}
