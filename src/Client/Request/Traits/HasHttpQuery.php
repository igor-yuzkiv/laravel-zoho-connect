<?php

namespace ZohoConnect\Client\Request\Traits;

trait HasHttpQuery
{
    /**
     * @var array
     */
    protected array $httpQuery = [];

    /**
     * @param array $query
     * @return $this
     */
    public function setHttpQuery(array $query): static
    {
        $this->httpQuery = $query;
        return $this;
    }

    /**
     * @return array
     */
    public function getHttpQuery(): array
    {
        return $this->httpQuery;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function addHttpQuery(string $key, mixed $value): static
    {
        $this->httpQuery[$key] = $value;
        return $this;
    }
}
