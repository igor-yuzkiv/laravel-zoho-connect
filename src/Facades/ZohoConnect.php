<?php

namespace ZohoConnect\Facades;

use Illuminate\Support\Facades\Facade;
use ZohoConnect\Interfaces\StorageDriver;

/**
 * @method static string getAccessToken(?string $client_id = null)
 * @method static StorageDriver storage()
 * @method static array dataCenterConfig(string $dataCenter = 'com')
 * @method static string callbackUrl()
 */
class ZohoConnect extends Facade
{
    public static function getFacadeAccessor()
    {
        return ZohoConnectAccessor::class;
    }
}
