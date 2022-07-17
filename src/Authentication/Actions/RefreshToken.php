<?php

namespace ZohoConnect\Authentication\Actions;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use ZohoConnect\Authentication\DTO\ClientCredentials;
use ZohoConnect\Facades\ZohoConnect;
use ZohoConnect\Utils\Url;

/**
 *
 */
class RefreshToken
{
    /**
     * @param ClientCredentials $client
     */
    public function __construct(
        private readonly ClientCredentials $client
    )
    {

    }

    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(): string
    {
        $response = $this->refreshRequest();

        $access_token = Arr::get($response, "access_token");

        ZohoConnect::storage()->put([
            "client_id"    => $this->client->client_id,
            "access_token" => $access_token,
        ]);

        return $access_token;
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function refreshRequest(): array
    {
        $dataCenter = ZohoConnect::getDataCenterConfig($this->client->data_center);

        $url = Url::of(config("zoho.connection.base_url") . $dataCenter['domain'])
            ->join("oauth")
            ->join(config("zoho.connection.version"))
            ->join("token")
            ->getValue();

        $client = new Client();
        $response = $client
            ->post($url, [
                RequestOptions::QUERY => [
                    "refresh_token" => $this->client->refresh_token,
                    "client_id"     => $this->client->client_id,
                    'client_secret' => $this->client->client_secret,
                    'grant_type'    => 'refresh_token'
                ]
            ]);

        $content = $response->getBody()->getContents();
        return json_decode($content, true);
    }
}
