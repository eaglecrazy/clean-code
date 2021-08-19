<?php

namespace CleanCode\Chapter14;

use MyCLabs\Enum\Enum;

/**
 * @method static ErrorCodeEnum OK()
 * @method static ErrorCodeEnum MISSING_STRING()
 * @method static ErrorCodeEnum MISSING_INTEGER()
 * @method static ErrorCodeEnum INVALID_INTEGER()
 * @method static ErrorCodeEnum MISSING_DOUBLE()
 * @method static ErrorCodeEnum INVALID_DOUBLE()
 * @method static ErrorCodeEnum UNEXPECTED_ARGUMENT()
 * @method static ErrorCodeEnum SCHEMA_UNKNOWN_ELEMENT()
 * @method static ErrorCodeEnum SCHEMA_BAD_CHARACTER()
 */
final class ErrorCodeEnum extends Enum
{
    private const OK = 'OK';
    private const MISSING_STRING = 'MISSING_STRING';
    private const MISSING_INTEGER = 'MISSING_INTEGER';
    private const INVALID_INTEGER = 'INVALID_INTEGER';
    private const MISSING_DOUBLE = 'MISSING_DOUBLE';
    private const INVALID_DOUBLE = 'INVALID_DOUBLE';
    private const UNEXPECTED_ARGUMENT = 'UNEXPECTED_ARGUMENT';
    private const SCHEMA_UNKNOWN_ELEMENT = 'SCHEMA_UNKNOWN_ELEMENT';
    private const SCHEMA_BAD_CHARACTER = 'SCHEMA_BAD_CHARACTER';
}
