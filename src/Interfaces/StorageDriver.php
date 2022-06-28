<?php

namespace ZohoConnect\Interfaces;

use JetBrains\PhpStorm\ArrayShape;
use ZohoConnect\ClientDto;

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
     * @return ClientDto
     */
    public function get(string $client_id): ClientDto;
}
