<?php

namespace ZohoConnect\Client\Request\Traits;

/**
 *
 */
trait HasHttpJson
{
    /**
     * @var array
     */
    protected array $httpJson = [];

    /**
     * @var string|null
     */
    protected ?string $httpJsonWrapper = null;

    /**
     * @return array
     */
    public function getHttpJson(): array
    {
        if ($this->httpJsonWrapper) {
            $httpJson = [];
            data_fill($httpJson, $this->httpJsonWrapper, $this->httpJson);
            return $httpJson;
        }

        return $this->httpJson;
    }

    /**
     * @param array $httpJson
     * @return $this
     */
    public function setHttpJson(array $httpJson): static
    {
        $this->httpJson = $httpJson;
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function addHttpJson(string $key, string $value): static
    {
        $this->httpJson[$key] = $value;
        return $this;
    }

    /**
     * @param string $wrapper
     * @return $this
     */
    public function wrapHttpJson(string $wrapper): static
    {
        $this->httpJsonWrapper = $wrapper;
        return $this;
    }
}
