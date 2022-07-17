<?php

namespace ZohoConnect\Facades;

use Illuminate\Support\Facades\Facade;
use ZohoConnect\Authentication\Contracts\StorageDriver;
use ZohoConnect\Authentication\DTO\ClientCredentials;

/**
 * @method static string getAccessToken(?string $client_id = null)
 * @method static StorageDriver storage()
 * @method static array getDataCenterConfig(string $dataCenter = 'com')
 * @method static string getCallbackUrl()
 * @method static string getClientId()
 * @method static ClientCredentials getClientCredentials(?string $client_id = null)
 * @method static string getClientDomain(?string $client_id = null)
 */
class ZohoConnect extends Facade
{
    public static function getFacadeAccessor()
    {
        return ZohoConnectAccessor::class;
    }
}
