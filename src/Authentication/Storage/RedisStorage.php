<?php

namespace ZohoConnect\Authentication\Storage;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;
use ZohoConnect\Authentication\Contracts\StorageDriver;
use ZohoConnect\Authentication\DTO\ClientCredentials;
use ZohoConnect\Exceptions\ClientNotFound;
use ZohoConnect\Exceptions\InvalidResponse;

/**
 *
 */
class RedisStorage implements StorageDriver
{
    /**
     * @param string $client_id
     * @return ClientCredentials
     * @throws ClientNotFound
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function get(string $client_id): ClientCredentials
    {
        $client = Redis::connection($this->getDatabase())
            ->get($this->getPrefix() . '.' . $client_id);

        $client = json_decode($client, true);

        if (empty($client) || !is_array($client)) {
            throw new ClientNotFound("Client not found. client_id: $client_id");
        }

        return new ClientCredentials($client);
    }

    /**
     * @param array $attributes
     * @return bool
     * @throws InvalidResponse
     */
    public function put(array $attributes): bool
    {
        $attributes = $this->mergeAttributes($attributes);

        Redis::connection($this->getDatabase())
            ->set(
                $this->getPrefix() . '.' . $attributes['client_id'],
                json_encode($attributes)
            );

        return true;
    }

    /**
     * @param array $attributes
     * @return array
     */
    private function mergeAttributes(array $attributes): array
    {
        $client_id = $attributes['client_id'];

        try {
            $exist = $this->get($client_id);
        } catch (\Exception $exception) {
            $exist = null;
        }

        if ($exist && is_array($exist)) {
            $attributes = array_merge($attributes, $exist);
        }

        $attributes['expire'] = Carbon::now()
            ->addSeconds(Arr::get($attributes, 'expires_in', 3600))
            ->toDateTimeLocalString();

        $attributes['updated_at'] = Carbon::now()->toDateTimeLocalString();

        return $attributes;
    }

    /**
     * @return string
     */
    private function getPrefix(): string
    {
        return config("zoho.connection.storage.redis.prefix", 'zoho-auth');
    }

    /**
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     * Get redis database connection
     */
    private function getDatabase()
    {
        return config("zoho.connection.storage.redis.database", 'default');
    }
}
