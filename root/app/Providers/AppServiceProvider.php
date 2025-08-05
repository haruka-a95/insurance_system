<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\InsurancePolicyInterface;
use App\Repositories\Contracts\InsuranceProductInterface;

use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\InsuranceRepository;
use App\Repositories\Eloquent\InsuranceProductRepository;

//アプリケーション全体で利用できるサービスや依存関係の設定を登録する役割
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //各インターフェースとリポジトリクラスを関連付けて依存性注入
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(InsurancePolicyInterface::class, InsuranceRepository::class);
        $this->app->bind(InsuranceProductInterface::class, InsuranceProductRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
