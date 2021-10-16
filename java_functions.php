<?php

/**
 * Get a substring in Java style.
 *
 * @param string $value
 * @param int $beginIndex
 * @param int $endIndex
 * @return string
 */
function substring(string $value, int $beginIndex, int $endIndex): string
{
    $length = strlen($value);

    checkBoundsBeginEnd($beginIndex, $endIndex, $length);

    if ($beginIndex === 0 && $endIndex === $length) {
        return $value;
    }

    $subLen = $endIndex - $beginIndex;

    return newString($value, $beginIndex, $subLen);
}

/**
 * Create a new string from a string in Java style.
 *
 * @param string $val
 * @param int $index
 * @param int $len
 * @return string
 */
function newString(string $val, int $index, int $len): string
{
    if ($len === 0) {
        return '';
    }

    $result = [];

    for ($i = $index; $i < $index + $len; $i++) {
        $result[] = $val[$i];
    }

    return implode($result);
}

/**
 * Check the boundaries of the beginning and end in Java style.
 *
 * @throws Exception
 */
function checkBoundsBeginEnd(int $begin, int $end, int $length): void
{
    if ($begin < 0 || $begin > $end || $end > $length) {
        throw new Exception("begin " . $begin . ", end " . $end . ", length " . $length);
    }
}
