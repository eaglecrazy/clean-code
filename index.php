<?php

/**
 * Clean code. Chapter 14 - "Successive refinement".
 * Args first version. Working with logical arguments only.
 */

namespace CleanCode;

require 'vendor/autoload.php';

try {
    $args = ['-l'];

    $arg = new Args("l", $args);

    $logging = $arg->getBool('l');

    executeApplication($logging);
} catch (ArgsException $e) {
    echo 'Argument error: ' . $e->getMessage();
}

/**
 * The program does something weird. :)
 *
 * @param bool $logging
 * @param int $port
 * @param string $directory
 */
function executeApplication(bool $logging = true, int $port = 0, string $directory = '/tmp'): void
{
    echo 'logging = ' . ($logging ? 'true' : 'false');
}
