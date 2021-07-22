<?php

/**
 * Clean code. Chapter 14 - "Successive refinement".
 * Args second version. Added string type arguments.
 */

namespace CleanCode;

use Exception;

require 'vendor/autoload.php';

try {
    //Unfortunately uncle Bob did not specify the format of the arguments in the book, so i use this format.
    $args = ['-l', '-d/tmp'];

    $schema = 'l,d*';

    $arg = new Args($schema, $args);

    $directory = $arg->getString('d');
    $logging = $arg->getBool('l');
    $port = 80;

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
