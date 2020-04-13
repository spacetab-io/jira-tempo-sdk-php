<?php

declare(strict_types=1);

namespace Spacetab\TempoSDK\API;

use Amp\Promise;
use DateTime;
use function Amp\call;

class Worklog extends HttpAPI implements WorklogInterface
{
    /**
     * @param int $worklogId
     * @return \Amp\Promise
     */
    public function get(int $worklogId): Promise
    {
        $this->logger->info("Worklog: Get one: {$worklogId}");

        return call(function () use ($worklogId) {
            /** @var \Amp\Http\Client\Response $response */
            $response = yield $this->httpClient->request(
                $this->configuredRequest->makeRequest("/worklogs/{$worklogId}")
            );

            return $this->handleResponse($response);
        });
    }

    /**
     * @inheritDoc
     */
    public function find(DateTime $from, DateTime $till, array $workers = [], array $projectKeys = [], array $params = []): Promise
    {
        $message = "Worklog: Find many worklogs from {$from->format('d.m.Y')} to {$till->format('d.m.Y')}";
        $this->logger->info($message, compact('workers', 'projectKeys', 'params'));

        $request = [
            'from'       => $from->format('Y-m-d'),
            'to'         => $from->format('Y-m-d'),
            'worker'     => $workers,
            'projectKey' => $projectKeys,
        ];

        $total = array_merge($request, $params);

        return call(function () use ($total) {
            /** @var \Amp\Http\Client\Response $response */
            $response = yield $this->httpClient->request(
                $this->configuredRequest->makeRequest('/worklogs/search', 'POST', json_encode($total))
            );

            return $this->handleResponse($response);
        });
    }
}
