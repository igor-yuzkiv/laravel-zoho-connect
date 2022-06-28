<?php

namespace ZohoConnect\Actions;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use ZohoConnect\ClientDto;
use ZohoConnect\Facades\ZohoConnect;
use ZohoConnect\Helpers\URLHelper;

/**
 *
 */
class RefreshToken
{
    /**
     * @param ClientDto $client
     */
    public function __construct(
        private ClientDto $client
    )
    {

    }

    /**
     * @return string
     */
    public function handle(): string
    {
        $response = $this->refreshRequst();

        $access_token = Arr::get($response, "access_token");

        ZohoConnect::storage()->put([
            "client_id"    => $this->client->client_id,
            "access_token" => $access_token,
        ]);

        return $access_token;
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function refreshRequst()
    {
        $dataCenter = ZohoConnect::dataCenterConfig($this->client->data_center);

        $url = URLHelper::join(
            config("zoho.connection.base_url") . $dataCenter['domain'],
            'oauth/' . config("zoho.connection.version") . "/token"
        );

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
