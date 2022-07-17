<?php

namespace ZohoConnect\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;
use ZohoConnect\Facades\ZohoConnect;
use Psr\Http\Message\ResponseInterface;
use ZohoConnect\Utils\Url;

/**
 *
 */
final class ZohoClient
{
    /**
     * @var string|null
     */
    private ?string $client_id = null;

    /**
     * @var array
     */
    private array $defaultHeaders = [];
    /**
     * @var array
     */
    private array $defaultQuery = [];

    /**
     * @var bool
     */
    protected bool $httpErrors = false;

    /**
     * @param string $host
     * @param string $defaultPath
     */
    public function __construct(
        private readonly string $host,
        private readonly string $defaultPath = '',
    )
    {
    }

    /**
     * @param string $host
     * @param string $defaultPath
     * @return static
     */
    public static function build(string $host, string $defaultPath = ''): self
    {
        return new self($host, $defaultPath);
    }

    /**
     * @return Client
     */
    public function getHttpClient(): Client
    {
        $stack = new HandlerStack(new CurlHandler());
        $stack->push($this->authorizationHeaderMiddleware());

        $clientOptions = [
            'base_uri'              => $this->host,
            'handler'               => $stack,
            RequestOptions::HEADERS => $this->defaultHeaders,
        ];

        return new Client($clientOptions);
    }

    /**
     * @return callable
     */
    private function authorizationHeaderMiddleware(): callable
    {
        $client_id = $this->client_id ?? ZohoConnect::getClientId();
        return function (callable $handler) use ($client_id) {
            return function (RequestInterface $request, array $options) use ($handler, $client_id) {
                $accessToken = ZohoConnect::getAccessToken($client_id);
                $request = $request->withHeader("Authorization", 'Zoho-oauthtoken ' . $accessToken);

                return $handler($request, $options);
            };
        };
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(string $method, string $uri, array $options): ResponseInterface
    {
        return $this->getHttpClient()->request(
            $method,
            Url::of($this->defaultPath)->join($uri)->getValue(),
            $this->getRequestOptions($options)
        );
    }

    /**
     * @param array $options
     * @return array
     */
    private function getRequestOptions(array $options = []): array
    {
        $defaultOptions = [
            RequestOptions::QUERY       => $this->defaultQuery,
            RequestOptions::HTTP_ERRORS => $this->httpErrors,
        ];

        return array_merge_recursive($defaultOptions, $options);
    }

    /**
     * @param string $client_id
     * @return $this
     */
    public function withClientId(string $client_id): self
    {
        $this->client_id = $client_id;
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function addDefaultHeader(string $key, string $value): self
    {
        $this->defaultHeaders[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function addDefaultQuery(string $key, string $value): self
    {
        $this->defaultQuery[$key] = $value;
        return $this;
    }

    /**
     * @param bool $httpErrors
     * @return $this
     */
    public function withErrors(bool $httpErrors): self
    {
        $this->httpErrors = $httpErrors;
        return $this;
    }
}
