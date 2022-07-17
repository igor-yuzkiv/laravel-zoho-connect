<?php

namespace ZohoConnect;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseProvider;
use ZohoConnect\Authentication\Contracts\StorageDriver;
use ZohoConnect\Authentication\Storage\EloquentStorage;
use ZohoConnect\Facades\ZohoConnectAccessor;

class ZohoConnectServiceProvider extends BaseProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->publish();
        $this->bindStorageDriver();
        $this->routes();
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->app->bind(ZohoConnectAccessor::class, function () {
            return new ZohoConnectAccessor();
        });
    }

    /**
     * @return void
     */
    private function publish()
    {
        $this->publishes([
            __DIR__ . '/../config'          => config_path('zoho'),
            __DIR__ . '/../resources/views' => resource_path('views/zoho/connection'),
        ]);
    }

    /**
     * @return void
     */
    private function bindStorageDriver()
    {
        $storage = config('zoho.connection.default_storage', "eloquent");
        $driver = config('zoho.connection.storage.'. $storage . '.driver', EloquentStorage::class);

        $this->app->bind(StorageDriver::class, $driver);
    }

    /**
     * @return void
     */
    private function routes()
    {
        Route::prefix('zoho/connection')
            ->middleware('web')
            ->namespace('ZohoConnect\Http\Controllers')
            ->group(__DIR__ . '/../routes.php');
    }
}
