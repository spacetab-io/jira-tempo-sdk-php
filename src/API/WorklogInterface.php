<?php

declare(strict_types=1);

namespace Spacetab\TempoSDK\API;

use Amp\Promise;
use DateTime;

interface WorklogInterface
{
    /**
     * @param int $worklogId
     * @return \Amp\Promise
     */
    public function get(int $worklogId): Promise;

    /**
     * @param \DateTime $from
     * @param \DateTime $till
     * @param array $workers
     * @param array $projectKeys
     * @param array $params
     * @return \Amp\Promise
     */
    public function find(DateTime $from, DateTime $till, array $workers = [], array $projectKeys = [], array $params = []): Promise;
}
