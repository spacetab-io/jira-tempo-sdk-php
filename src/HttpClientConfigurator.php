<?php

declare(strict_types=1);

namespace Spacetab\TempoSDK;

use Amp\Http\Client\Connection\UnlimitedConnectionPool;
use Amp\Http\Client\HttpClient;
use Amp\Http\Client\InterceptedHttpClient;
use Amp\Http\Client\Interceptor\DecompressResponse;
use Amp\Http\Client\Interceptor\FollowRedirects;
use Amp\Http\Client\Interceptor\RetryRequests;
use Amp\Http\Client\Interceptor\SetRequestHeaderIfUnset;
use Amp\Http\Client\PooledHttpClient;

final class HttpClientConfigurator
{
    /**
     * Retry requests count.
     */
    private const RETRY_REQUESTS   = 3;
    private const FOLLOW_REDIRECTS = 10;
    private const TEMPO_REST_PATH = '/rest/tempo-timesheets/4';

    /**
     * @var string
     */
    private string $basicAuthUsername;

    /**
     * @var string
     */
    private string $basicAuthPassword;

    /**
     * @var string
     */
    private string $endpoint;

    /**
     * @var \Amp\Http\Client\Connection\UnlimitedConnectionPool
     */
    private UnlimitedConnectionPool $pool;

    /**
     * HttpClientConfigurator constructor.
     */
    public function __construct()
    {
        $this->pool = new UnlimitedConnectionPool;
    }

    /**
     * @return \Amp\Http\Client\HttpClient
     */
    public function createConfiguredHttpClient(): HttpClient
    {
        /** @var PooledHttpClient $client */
        $client = new PooledHttpClient($this->pool);

        $interceptors = [
            new SetRequestHeaderIfUnset('Accept', 'application/json'),
            new SetRequestHeaderIfUnset('Content-Type', 'application/json'),
            new SetRequestHeaderIfUnset('Authorization', $this->getAuthorizationHeaderValue()),
            new SetRequestHeaderIfUnset('User-Agent', sprintf('Tempo PHP SDK / v%s', TempoSDK::VERSION)),
            new DecompressResponse()
        ];

        foreach ($interceptors as $interceptor) {
            $client = $client->intercept($interceptor);
        }

        $client = new InterceptedHttpClient($client, new RetryRequests(self::RETRY_REQUESTS));
        $client = new InterceptedHttpClient($client, new FollowRedirects(self::FOLLOW_REDIRECTS));

        return new HttpClient($client);
    }

    /**
     * @return \Spacetab\TempoSDK\ConfiguredRequest
     */
    public function createConfiguredHttpRequest(): ConfiguredRequest
    {
        $endpoint = trim($this->endpoint, '/') . self::TEMPO_REST_PATH;

        return (new ConfiguredRequest($endpoint));
    }

    /**
     * @param string $endpoint
     * @return \Spacetab\TempoSDK\HttpClientConfigurator
     */
    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setBasicUsername(string $username): self
    {
        $this->basicAuthUsername = $username;

        return $this;
    }

    /**
     * @param string $password
     * @return \Spacetab\TempoSDK\HttpClientConfigurator
     */
    public function setBasicPassword(string $password): self
    {
        $this->basicAuthPassword = $password;

        return $this;
    }

    /**
     * @return string
     */
    private function getAuthorizationHeaderValue(): string
    {
        return 'Basic ' . base64_encode("$this->basicAuthUsername:$this->basicAuthPassword");
    }
}
