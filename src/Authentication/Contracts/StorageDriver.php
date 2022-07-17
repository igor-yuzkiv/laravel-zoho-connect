<?php

namespace ZohoConnect\Authentication\Contracts;

use ZohoConnect\Authentication\DTO\ClientCredentials;

/**
 *
 */
interface StorageDriver
{
    /**
     * @param array $attributes
     * @return bool
     */
    public function put(array $attributes): bool;

    /**
     * @param string $client_id
     * @return ClientCredentials
     */
    public function get(string $client_id): ClientCredentials;
}
