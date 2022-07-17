<?php

namespace ZohoConnect\Utils;

use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Str;
use Psr\Http\Message\UriInterface;

/**
 *
 */
final class Url
{
    /**
     * @var string
     */
    private string $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @param string $value
     * @return Url
     */
    public static function of(string $value): self
    {
        return new self($value);
    }

    /**
     * @param string $pattern
     * @return bool
     */
    public function matches(string $pattern): bool
    {
        return Str::is(Str::start($pattern, '*'), $this->value);
    }

    /**
     * @param string $endpoint
     * @return $this
     */
    public function join(string $endpoint): self
    {
        if ($endpoint !== DIRECTORY_SEPARATOR) {
            $endpoint = ltrim($endpoint, DIRECTORY_SEPARATOR . ' ');
        }

        $requiresTrailingSlash = !empty($endpoint) && $endpoint !== DIRECTORY_SEPARATOR;

        $baseEndpoint = rtrim($this->value, DIRECTORY_SEPARATOR . ' ');

        $baseEndpoint = $requiresTrailingSlash
            ? $baseEndpoint . DIRECTORY_SEPARATOR
            : $baseEndpoint;

        $this->value = $baseEndpoint . $endpoint;

        return $this;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setQueryPrams(array $params = []): self
    {
        if (empty($params)) {
            return $this;
        }

        $this->value = $this->value . "?" . http_build_query($params);

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
