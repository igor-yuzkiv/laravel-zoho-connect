<?php

namespace ZohoConnect\Authentication\Actions;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use JetBrains\PhpStorm\ArrayShape;
use ZohoConnect\Facades\ZohoConnect;
use ZohoConnect\Utils\Url;

/**
 * Step 3: Generate Access And Refresh Token
 * After getting code from the above step, make a POST request for the following URL with given params, to generate the access_token.
 * https://accounts.zoho.com/oauth/v2/token?
 */
class GenerateAccessToken
{
    /**
     * @var string
     */
    private string $dataCenter = 'us';

    /**
     * @param string $id
     * @param string $secret
     * @param string $code
     */
    public function __construct(
        private readonly string $id,
        private readonly string $secret,
        private readonly string $code,
    )
    {

    }

    /**
     * @param string $dataCenter
     * @return $this
     */
    public function useDataCenter(string $dataCenter): self
    {
        $this->dataCenter = $dataCenter;
        return $this;
    }

    /**
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(): bool
    {
        $response = $this->getResponse();

        return ZohoConnect::storage()
            ->put([
                "client_id"     => $this->id,
                "client_secret" => $this->secret,
                "access_token"  => $response['access_token'],
                "refresh_token" => $response['refresh_token'],
                "data_center"   => $this->dataCenter,
                "expire"        => $response['expires_in'],
            ]);
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    #[ArrayShape([
        'access_token'  => 'string',
        'refresh_token' => 'string',
        'api_domain'    => 'string',
        'token_type'    => 'string',
        'expires_in'    => 'int',
    ])]
    private function getResponse(): array
    {
        $dataCenter = ZohoConnect::getDataCenterConfig($this->dataCenter);

        $url = Url::of(config("zoho.connection.base_url") . $dataCenter['domain'])
            ->join("oauth")
            ->join(config("zoho.connection.version"))
            ->join("token")
            ->getValue();

        $query = [
            'grant_type'    => 'authorization_code',
            'client_id'     => $this->id,
            'client_secret' => $this->secret,
            'redirect_uri'  => ZohoConnect::getCallbackUrl(),
            'code'          => $this->code,
        ];

        $response = (new Client())
            ->post($url, [
                RequestOptions::QUERY => $query,
            ]);


        return json_decode($response->getBody()->getContents(), true);
    }
}
