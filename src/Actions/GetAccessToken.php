<?php

namespace ZohoConnect\Actions;

use Carbon\Carbon;
use ZohoConnect\Exceptions\ClientNotFound;
use ZohoConnect\Facades\ZohoConnect;

/**
 *
 */
class GetAccessToken
{
    /**
     * @param string|null $client_id
     */
    public function __construct(private ?string $client_id = null)
    {
        if (empty($this->client_id)) {
            $this->client_id = config("zoho.connection.default_client");
        }
    }

    /**
     * @return string
     * @throws ClientNotFound
     */
    public function handle(): string
    {
        $client = ZohoConnect::storage()->get($this->client_id);

        if (empty($client)) {
            throw new ClientNotFound();
        }

        if ($this->isExpired($client->expire)) {
            return (new RefreshToken($client))->handle();
        }

        return $client->access_token;
    }

    /**
     * @param string $expire
     * @return bool
     */
    public function isExpired(string $expire): bool
    {
        $expire = Carbon::createFromDate($expire);
        $now = Carbon::now();
        return ($now->diffInSeconds($expire, false) < 150);
    }
}
