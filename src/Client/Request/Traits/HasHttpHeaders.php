<?php

namespace ZohoConnect\Client\Request\Traits;

/**
 *
 */
trait HasHttpHeaders
{
    /**
     * @var array
     */
    protected array $httpHeaders = [];

    /**
     * @param array $headers
     * @return $this
     */
    public function setHttpHeaders(array $headers): static
    {
        $this->httpHeaders = $headers;
        return $this;
    }

    /**
     * @return array
     */
    public function getHttpHeaders(): array
    {
        return $this->httpHeaders;
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function addHttpHeader(string $key, string $value): static
    {
        $this->httpHeaders[$key] = $value;
        return $this;
    }
}
