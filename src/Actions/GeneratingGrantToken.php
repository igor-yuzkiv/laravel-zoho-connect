<?php

namespace ZohoConnect\Actions;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use JetBrains\PhpStorm\ArrayShape;
use ZohoConnect\Facades\ZohoConnect;
use ZohoConnect\Helpers\URLHelper;

/**
 * Step 2: Generating Grant Token
 * Redirect to the following authorization URL with the given params
 */
class GeneratingGrantToken
{
    /**
     * @var string
     */
    private string $dataCenter = 'us';

    /**
     * @param string $id
     * @param string $secret
     * @param array $scopes
     */
    public function __construct(
        private readonly string $id,
        private readonly string $secret,
        private readonly array  $scopes
    )
    {

    }

    /**
     * @param string $dataCenter
     * @return $this
     */
    public function useDataCenter(string $dataCenter = 'us'): self
    {
        $this->dataCenter = $dataCenter;
        return $this;
    }

    /**
     * @return RedirectResponse
     */
    public function handle(): RedirectResponse
    {
        $url = $this->authorizationURL();
        return redirect($url);
    }

    /**
     * @return string
     */
    private function authorizationURL()
    {
        $dataCenter = ZohoConnect::dataCenterConfig($this->dataCenter);

        $url = URLHelper::join(
            config("zoho.connection.base_url") . $dataCenter['domain'],
            'oauth/' . config("zoho.connection.version") . "/auth"
        );

        return URLHelper::setQueryPrams(
            $url,
            [
                'scope'         => implode(",", $this->scopes),
                'client_id'     => $this->id,
                'client_secret' => $this->secret,
                'state'         => 'code',
                'response_type' => 'code',
                'redirect_uri'  => URLHelper::join(config('app.url'), "/zoho/connection/callback"),
                'access_type'   => 'offline'
            ]
        );
    }
}
