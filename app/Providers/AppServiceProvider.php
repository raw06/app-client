<?php

namespace App\Providers;

use App\Lib\DbSessionStorage;
use App\Services\ShopSettingApi;
use Illuminate\Support\ServiceProvider;
use Shopify\ApiVersion;
use Shopify\Context;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Context::initialize(
            env('SHOPIFY_API_KEY', 'not_defined'),
            env('SHOPIFY_API_SECRET', 'not_defined'),
            env('SHOPIFY_PERMISSIONS', 'not_defined'),
            env('APP_HOST', 'ic-app.smartifyapps.com'),
            new DbSessionStorage(),
            ApiVersion::LATEST,
            false,
            false,
            null,
            '',
            null,
        );
    }

}
