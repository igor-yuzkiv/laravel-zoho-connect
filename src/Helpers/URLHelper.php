<?php

namespace ZohoConnect\Helpers;

use Illuminate\Support\Str;

class URLHelper
{
    /**
     * Check if a URL matches a given pattern
     *
     * @param string $pattern
     * @param string $value
     * @return bool
     */
    public static function matches(string $pattern, string $value): bool
    {
        return Str::is(Str::start($pattern, '*'), $value);
    }

    /**
     * Join a base url and an endpoint together.
     *
     * @param string $baseUrl
     * @param string $endpoint
     * @return string
     */
    public static function join(string $baseUrl, string $endpoint): string
    {
        if ($endpoint !== DIRECTORY_SEPARATOR) {
            $endpoint = ltrim($endpoint, DIRECTORY_SEPARATOR . ' ');
        }

        $requiresTrailingSlash = !empty($endpoint) && $endpoint !== DIRECTORY_SEPARATOR;

        $baseEndpoint = rtrim($baseUrl, DIRECTORY_SEPARATOR . ' ');

        $baseEndpoint = $requiresTrailingSlash
            ? $baseEndpoint . DIRECTORY_SEPARATOR
            : $baseEndpoint;

        return $baseEndpoint . $endpoint;
    }

    public static function setQueryPrams(string $url, array $params = []): string
    {
        if (empty($params)) {
            return $url;
        }

        return  $url . "?" . http_build_query($params);
    }

}
