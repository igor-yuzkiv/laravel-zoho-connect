<?php

namespace ZohoConnect\Authentication\Actions;

use Illuminate\Http\RedirectResponse;
use ZohoConnect\Facades\ZohoConnect;
use ZohoConnect\Utils\Url;

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
        //
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
    private function authorizationURL(): string
    {
        $dataCenter = ZohoConnect::getDataCenterConfig($this->dataCenter);

        return Url::of(config("zoho.connection.base_url") . $dataCenter['domain'],)
            ->join("oauth")
            ->join(config("zoho.connection.version"))
            ->join("auth")
            ->setQueryPrams([
                'scope'         => implode(",", $this->scopes),
                'client_id'     => $this->id,
                'client_secret' => $this->secret,
                'state'         => 'code',
                'response_type' => 'code',
                'redirect_uri'  => Url::of(config('app.url'))->join("/zoho/connection/callback")->getValue(),
                'access_type'   => 'offline'
            ])
            ->getValue();
    }
}
