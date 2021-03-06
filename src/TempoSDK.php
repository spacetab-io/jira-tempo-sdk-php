<?php

declare(strict_types=1);

namespace Spacetab\TempoSDK;

use Amp\Http\Client\HttpClient;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Spacetab\TempoSDK\API\Worklog;
use Spacetab\TempoSDK\API\WorklogInterface;

class TempoSDK implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public const VERSION = '1.0.0b';

    /**
     * @var \Spacetab\TempoSDK\HttpClientConfigurator
     */
    private HttpClientConfigurator $clientConfigurator;

    /**
     * @var \Amp\Http\Client\HttpClient
     */
    private HttpClient $httpClient;

    /**
     * @var \Spacetab\TempoSDK\ConfiguredRequest
     */
    private ConfiguredRequest $httpRequest;

    /**
     * TempoSDK constructor.
     *
     * @param \Spacetab\TempoSDK\HttpClientConfigurator $clientConfigurator
     * @param \Psr\Log\LoggerInterface|null $logger
     */
    public function __construct(HttpClientConfigurator $clientConfigurator, ?LoggerInterface $logger = null)
    {
        $this->clientConfigurator = $clientConfigurator;
        $this->httpClient         = $clientConfigurator->createConfiguredHttpClient();
        $this->httpRequest        = $clientConfigurator->createConfiguredHttpRequest();
        $this->logger             = $logger ?: new NullLogger();
    }

    /**
     * @param string $endpoint
     * @param string $basicUsername
     * @param string $basicPassword
     *
     * @return static
     */
    public static function new(string $endpoint, string $basicUsername, string $basicPassword): self
    {
        $configurator = (new HttpClientConfigurator())
            ->setEndpoint($endpoint)
            ->setBasicUsername($basicUsername)
            ->setBasicPassword($basicPassword);

        return new TempoSDK($configurator);
    }

    public function worklogs(): WorklogInterface
    {
        return new Worklog($this->httpClient, $this->httpRequest, $this->logger);
    }
}
