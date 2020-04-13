<?php

declare(strict_types=1);

namespace Spacetab\TempoSDK\Exception;

class UnknownErrorException extends SdkErrorException
{
    public static function unknownError()
    {
        return new self('Unknown error occurred.');
    }
}
