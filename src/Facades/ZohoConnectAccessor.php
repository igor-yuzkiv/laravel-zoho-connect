<?php

namespace ZohoConnect\Facades;

use JetBrains\PhpStorm\ArrayShape;
use ZohoConnect\Authentication\Actions\GetAccessToken;
use ZohoConnect\Authentication\Contracts\StorageDriver;
use ZohoConnect\Authentication\DTO\ClientCredentials;
use ZohoConnect\Utils\Url;

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
    public function getDataCenterConfig(string $dataCenter = 'us'): array
    {
        return config("zoho.connection.data_center.{$dataCenter}");
    }

    /**
     * @return string
     */
    public function getCallbackUrl(): string
    {
        return Url::of(config('app.url'))
            ->join("/zoho/connection/callback")
            ->getValue();
    }

    /**
     * @return StorageDriver
     */
    public function storage(): StorageDriver
    {
        return app(StorageDriver::class);
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return config("zoho.connection.default_client");
    }

    /**
     * @param string|null $client_id
     * @return ClientCredentials
     */
    public function getClientCredentials(?string $client_id = null): ClientCredentials
    {
        return ZohoConnect::storage()->get($client_id ?? $this->getClientId());
    }

    /**
     * @param string|null $client_id
     * @return string
     */
    public function getClientDomain(?string $client_id = null): string
    {
        $dataCenter = $this->getDataCenterConfig($this->getClientCredentials($client_id)->data_center);
        return $dataCenter["domain"];
    }
}
