<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        // \APP\Model\User::class=>\App\Policies\UserPolicy::class, 错误的
        \App\Models\User::class=>\App\Policies\UserPolicy::class,
        \App\Models\Status::class  => \App\Policies\StatusPolicy::class,


        // 日这个的路径写错了，导致授权不到
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //
    }
}
