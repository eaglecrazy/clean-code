<?php

/**
 * Clean code. Chapter 14 - "Successive refinement".
 * Refactoring process.
 */

namespace CleanCode;

use Exception;

require 'vendor/autoload.php';

try {
    //Unfortunately uncle Bob did not specify the format of the arguments in the book, so i use this format.
    $args = ['-l', '-p80', '-d/tmp'];

    $schema = 'l,p#,d*';

    $arg = new Args($schema, $args);

    $directory = $arg->getString('d');
    $logging = $arg->getBoolean('l');
    $port = $arg->getInt('p');

    executeApplication($logging, $port, $directory);
} catch (Exception $e) {
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
    echo 'logging = ' . ($logging ? 'true' : 'false') . PHP_EOL;
    echo 'port = ' . $port . PHP_EOL;
    echo 'directory = ' . $directory . PHP_EOL;
}
