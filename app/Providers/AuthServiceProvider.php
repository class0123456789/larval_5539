<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Carbon\Carbon;
use League\Flysystem\Exception;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        //if (!empty($_SERVER['SCRIPT_NAME']) && strtolower($_SERVER['SCRIPT_NAME']) === 'artisan') {
        //    return false;
        //}
        //\\Gate::before()
        //Gate::before(function ($user, $ability) {
        //    if ($user->id === 1) {
         //       return true;
         //   }
        //});
        $this->registerPolicies();
        Passport::routes();

        //3、配置 令牌生命周期
        Passport::tokensExpireIn(Carbon::now()->addDays(15));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        //精简撤销令牌
        Passport::pruneRevokedTokens();

        //$permissions = \App\Models\Permission::with('slug')->get();//取得所有权限
        //$permissions = \App\Models\Permission::all();//取得所有权限
        //var_dump($permissions);
        //foreach ($permissions as $permission) {
        //    Gate::define($permission->slug, function ($user) use ($permission) {
        //        return $user->hasPermission($permission);
        //    });
        //}


        //
    }
}
