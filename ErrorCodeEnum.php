<?php

namespace CleanCode;

use MyCLabs\Enum\Enum;

/**
 * @method static ErrorCodeEnum OK()
 * @method static ErrorCodeEnum MISSING_STRING()
 * @method static ErrorCodeEnum MISSING_INTEGER()
 * @method static ErrorCodeEnum INVALID_INTEGER()
 * @method static ErrorCodeEnum UNEXPECTED_ARGUMENT()
 */
final class ErrorCodeEnum extends Enum
{
    private const OK = 'OK';
    private const MISSING_STRING = 'MISSING_STRING';
    private const MISSING_INTEGER = 'MISSING_INTEGER';
    private const INVALID_INTEGER = 'INVALID_INTEGER';
    private const UNEXPECTED_ARGUMENT = 'UNEXPECTED_ARGUMENT';
}
