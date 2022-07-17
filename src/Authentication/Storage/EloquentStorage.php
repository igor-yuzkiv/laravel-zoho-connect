<?php

namespace ZohoConnect\Authentication\Storage;

use Illuminate\Support\Arr;
use ZohoConnect\Authentication\Contracts\StorageDriver;
use ZohoConnect\Authentication\DTO\ClientCredentials;
use ZohoConnect\Exceptions\ClientNotFound;
use ZohoConnect\Models\ConnectionModel;

/**
 *
 */
class EloquentStorage implements StorageDriver
{
    /**
     * @param array $attributes
     * @return bool
     */
    public function put(array $attributes): bool
    {
        $attributes['expire'] = \Carbon\Carbon::now()
            ->addSeconds(Arr::get($attributes, 'expires_in', 3600));

        app(ConnectionModel::class)
            ->updateOrCreate([
                'client_id' => $attributes['client_id']],
                $attributes
            );

        return true;
    }

    /**
     * @param string $client_id
     * @return ClientCredentials
     * @throws ClientNotFound
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function get(string $client_id): ClientCredentials
    {
        $client = ConnectionModel::query()
            ->where('client_id', $client_id)
            ->first();


        if (empty($client)) {
            throw new ClientNotFound("Client not found. client_id: $client_id");
        }

        return new ClientCredentials($client->toArray());
    }
}
