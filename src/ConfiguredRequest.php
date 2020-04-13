<?php

declare(strict_types=1);

namespace Spacetab\TempoSDK;

use Amp\Http\Client\Request;
use League\Uri\Uri;

final class ConfiguredRequest
{
    /**
     * @var \Psr\Http\Message\UriInterface|Uri
     */
    private $baseUri = null;

    /**
     * ConfiguredRequest constructor.
     *
     * @param string $baseUri
     */
    public function __construct(string $baseUri)
    {
        $this->baseUri = $baseUri;
    }

    /**
     * @param string $path
     * @param string $method
     * @param string|null $body
     * @return \Amp\Http\Client\Request
     */
    public function makeRequest(string $path, string $method = 'GET', ?string $body = null)
    {
        $uri = Uri::createFromString($this->baseUri . $path);

        return new Request($uri, $method, $body);
    }
}
