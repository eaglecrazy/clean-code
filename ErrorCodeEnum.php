<?php

namespace CleanCode;

use MyCLabs\Enum\Enum;

/**
 * @method static ErrorCodeEnum OK()
 * @method static ErrorCodeEnum MISSING_STRING()
 */
final class ErrorCodeEnum extends Enum
{
    private const OK = 'OK';
    private const MISSING_STRING = 'MISSING_STRING';
}
