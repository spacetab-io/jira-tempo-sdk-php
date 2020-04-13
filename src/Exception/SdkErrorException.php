<?php

declare(strict_types=1);

namespace Spacetab\TempoSDK\Exception;

use Exception;

class SdkErrorException extends Exception
{
    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }
}
