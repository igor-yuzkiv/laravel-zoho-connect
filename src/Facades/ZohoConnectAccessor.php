<?php

namespace ZohoConnect\Facades;

use JetBrains\PhpStorm\ArrayShape;
use ZohoConnect\Actions\GetAccessToken;
use ZohoConnect\Helpers\URLHelper;
use ZohoConnect\Interfaces\StorageDriver;

/**
 *
 */
class ZohoConnectAccessor
{
    /**
     * @param string|null $client_id
     * @return string
     * @throws \ZohoConnect\Exceptions\ClientNotFound
     */
    public function getAccessToken(?string $client_id = null): string
    {
        return (new GetAccessToken($client_id))->handle();
    }

    /**
     * @param string $dataCenter
     * @return array
     */
    #[ArrayShape([
        'title'    => 'string',
        'domain'   => 'string',
        'location' => 'string',
    ])]
    public function dataCenterConfig(string $dataCenter = 'us'): array
    {
        return config("zoho.connection.data_center.{$dataCenter}");
    }

    /**
     * @return string
     */
    public function callbackUrl(): string
    {
        return URLHelper::join(config('app.url'), "/zoho/connection/callback");
    }

    /**
     * @return StorageDriver
     */
    public function storage(): StorageDriver
    {
        return app(StorageDriver::class);
    }
}
