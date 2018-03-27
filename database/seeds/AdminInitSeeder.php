<?php

use Illuminate\Database\Seeder;

class AdminInitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\AdminUser::truncate();
        \App\Models\Permission::truncate();
        \App\Models\Role::truncate();
        \App\Models\Menu::truncate();
        \DB::select(
            <<<SQL
                truncate admin_role_user;
SQL
        );
        \DB::select(
            <<<SQL
                truncate admin_permission_user;
SQL
        );
        \DB::select(
            <<<SQL
                truncate admin_permission_role;
SQL
        );
      
                \DB::select(
            <<<SQL
                truncate admin_kinds;
SQL
        );
            \DB::select(
            <<<SQL
                truncate admin_institutions;
SQL
        );
            \DB::select(
            <<<SQL
                truncate admin_user_institution;
SQL
        );

       // dd('11111');
        
               
        

        $admin = new \App\Models\AdminUser();
        $admin->name = 'root';
        $admin->email = 'root@admin.com';
        $admin->password = bcrypt('root');
        $admin->save();
        
         \App\Models\AdminUser::Create([
                                'name' => 'test1',
                                'email' => 'test1@admin.com',
                                'password' => bcrypt('root'),
                        ]);
        \App\Models\AdminUser::Create([
                                'name' => 'test2',
                                'email' => 'test2@admin.com',
                                'password' => bcrypt('root'),
                        ]); 
        
        
                
       
        \DB::select(
            <<<SQL
                INSERT INTO `admin_roles` ( `name`, `slug`, `description`)
VALUES
	('超级管理', 'admin', '超级管理'),
	('功能测试', 'test', '功能测试'),
	('日志管理', 'log', '日志管理'),
	('控制台', 'dash', '控制台');
SQL
        );
        
                
       
        \DB::select(
            <<<SQL
                INSERT INTO `admin_role_user` ( `role_id`, `user_id`)
VALUES
	(1,1),
        (2,1),        
	(1,2),
	(4,3),
	(2,3);
SQL
        );
        
         //获取全部的后台路由
         $routes = Route::getRoutes()->getRoutesByMethod();

               //  $routes = Route::getRoutes()->getRoutesByName();
                 $attr =[];
                 //$pattern='/\//';  /*因为/为特殊字符，需要转移*/
                 //$surl='';
                 //dd($routes,Route::current(),Route::current()->getAction());
                 //取出所有的admin开头的地址
                foreach ($routes as $k0=>$v0) {            
                    foreach ($v0 as $k1=>$v1) {
                        if(starts_with($k1, 'admin/') ){                            
                           // $str=preg_split ($pattern, $k1);
                           // $surl='';
                           // foreach($str as $strk=>$strkv)
                           // {
                           //     if(!starts_with($strkv, '{')){ //取消当中的参数
                           //         $surl.="/".$strkv;                            
                           //     }
                            //}
                           // $surl = substr($surl,1);//把最前面的'/'去掉
                            //dd($surl);
                          //if(!isset($attr[$k0][$k1])){ $attr[$k0][$k1]=$v1->uri; } 
                           //if(!isset($attr[$surl])){ $attr[$surl]=$v1->uri; } 
                            if(isset($v1->action['as'])){
                                if(!isset($attr[$v1->action['as']])){ 
                                   $attr[$v1->action['as']]=$v1->uri; 
                                   //dd($attr[$v1->action['as']]);
                               } 
                            } else {
                                 if(!isset($attr[$k1])){
                                   $attr[$k1]=$v1->uri; 
                                    //dd($attr[$k1]);
                               } 
                            } 
                           //unset($str);
                        }                                      
                    }
                    // $att[] =$route;
                    //$route->prepareForSerialization();
                }
                //unset($surl);
                //unset($pattern);
                //dd($attr);
               
                
                $user = \App\Models\AdminUser::find(1);
                $role = \App\Models\Role::find(1);
                $role2 = \App\Models\Role::find(2);
                foreach($attr as $k=>$v){//权限表增加
                    $newPerm = \App\Models\Permission::Create(
                            [
                                'slug' => $k,
                                'name' => $k,
                                'description' =>$v,
                        ]);
                      $user->assignPermission($newPerm);//给用户分配
                      $role->grantPermission($newPerm);
                      $role2->grantPermission($newPerm);
                }
                unset($attr);
                
                 $newPerm = \App\Models\Permission::Create(
                            [
                                'name' => "系统管理",
                                'slug' => "system/manage",
                                'description' =>"系统管理",
                        ]);
                 $user->assignPermission($newPerm);
                 
                                  $newPerm = \App\Models\Permission::Create(
                            [
                                'name' => "用户管理",
                                'slug' => "user/index",
                                'description' =>"用户管理",
                        ]);
                 $user->assignPermission($newPerm);
                 $newPerm = \App\Models\Permission::Create(
                            [
                                'name' => "角色管理",
                                'slug' => "role/index",
                                'description' =>"角色管理",
                        ]);
                 $user->assignPermission($newPerm);
                 $newPerm = \App\Models\Permission::Create(
                            [
                                'name' => "菜单管理",
                                'slug' => "menu/index",
                                'description' =>"菜单管理",
                        ]);
                 $user->assignPermission($newPerm);
                 $newPerm = \App\Models\Permission::Create(
                            [
                                'name' => "权限管理",
                                'slug' => "permission/index",
                                'description' =>"权限管理",
                        ]);
                 $user->assignPermission($newPerm);
                


                
                
                       
        \DB::select(
            <<<SQL
                INSERT INTO `menus` (`id`, `pid`, `name`, `icon`, `slug`, `url`, `active`, `description`, `sort`, `created_at`, `updated_at`) VALUES
(1, 0, '控制台', 'fa-dashboard', 'admin/dash', 'admin/dash', 'admin/dash', '控制台', 1, '2017-11-18 12:41:14', '2017-11-27 06:59:09'),
(2, 0, '系统日志', 'fa-dashboard', 'admin/index', 'admin/index', 'admin/index', '系统日志', 1, '2017-11-18 12:41:14', '2017-11-27 06:59:09'),
(3, 0, '系统管理', 'fa-cog', 'system/manage', '', 'admin/role*,admin/permission*,admin/user*,admin/menu*', '系统功能管理', 9, '2017-11-18 12:41:14', '2017-11-18 12:41:14'),
(4, 3, '用户管理', 'fa-users', 'user/index', 'admin/user', 'admin/user*', '显示用户管理', 0, '2017-11-18 12:41:14', '2017-11-18 12:41:14'),
(5, 3, '角色管理', 'fa-male', 'role/index', 'admin/role', 'admin/role*', '显示角色管理', 0, '2017-11-18 12:41:14', '2017-11-18 12:41:14'),
(6, 3, '权限管理', 'fa-paper-plane', 'permission/index', 'admin/permission', 'admin/permission*', '显示权限管理', 0, '2017-11-18 12:41:14', '2017-11-18 12:41:14'),
(7, 3, '菜单管理', 'fa-navicon', 'menu/index', 'admin/menu', 'admin/menu*', '显示菜单管理', 0, '2017-11-18 12:41:14', '2017-11-18 12:41:14');


SQL
        );
        
                \DB::select(
            <<<SQL
                INSERT INTO `admin_kinds` (`name`) VALUES ('未定义');
SQL
        );
           \DB::select(
            <<<SQL
                INSERT INTO `admin_institutions` (`name`,`parent_id`,`kind_id`) VALUES ('全辖区',0,1);
SQL
        );
                      \DB::select(
            <<<SQL
                INSERT INTO `admin_user_institution` (`user_id`,`institution_id`) VALUES (1,1);
SQL
        );
    }
}
