<?php

namespace ZohoConnect\Client\Request;

use GuzzleHttp\RequestOptions;
use JetBrains\PhpStorm\ArrayShape;
use ZohoConnect\Client\Request\Traits\HasHttpHeaders;
use ZohoConnect\Client\Request\Traits\HasHttpJson;
use ZohoConnect\Client\Request\Traits\HasHttpQuery;
use ZohoConnect\Client\ZohoClient;
use Psr\Http\Message\ResponseInterface;

/**
 *
 */
abstract class ZohoRequest
{
    use HasHttpHeaders, HasHttpQuery, HasHttpJson;

    /**
     * @var string
     */
    protected string $endpoint;

    /**
     * @return ZohoClient
     */
    abstract public function getClient(): ZohoClient;

    /**
     * @param string $endpoint
     * @return $this
     */
    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @return string
     */
    protected function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(): ResponseInterface
    {
        return $this->getClient()->request(
            "GET",
            $this->getEndpoint(),
            $this->getRequestOptions()
        );
    }

    /**
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post(): ResponseInterface
    {
        return $this->getClient()->request(
            "POST",
            $this->getEndpoint(),
            $this->getRequestOptions()
        );
    }

    /**
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function put(): ResponseInterface
    {
        return $this->getClient()->request(
            "PUT",
            $this->getEndpoint(),
            $this->getRequestOptions()
        );
    }

    /**
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(): ResponseInterface
    {
        return $this->getClient()
            ->request(
                "DELETE",
                $this->getEndpoint(),
                $this->getRequestOptions()
            );
    }

    /**
     * @return array
     */
    #[ArrayShape([RequestOptions::QUERY => "array", RequestOptions::HEADERS => "array", RequestOptions::JSON => "array"])]
    protected function getRequestOptions(): array
    {
        return [
            RequestOptions::QUERY   => $this->getHttpQuery(),
            RequestOptions::HEADERS => $this->getHttpHeaders(),
            RequestOptions::JSON    => $this->getHttpJson(),
        ];
    }
}
