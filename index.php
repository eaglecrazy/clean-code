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
    $args = ['-l', '-p80', '-d/tmp', '-f3.14'];

    $schema = 'l,p#,d*,f##';

    $arg = new Args($schema, $args);

    $directory = $arg->getString('d');
    $logging = $arg->getBoolean('l');
    $port = $arg->getInt('p');
    $double = $arg->getDouble('f');

    executeApplication($logging, $port, $directory, $double);
} catch (Exception $e) {
    echo 'Argument error: ' . $e->getMessage();
}

/**
 * The program does something weird. :)
 *
 * @param bool $logging
 * @param int $port
 * @param string $directory
 * @param float $double
 */
function executeApplication(bool $logging, int $port, string $directory, float $double): void
{
    echo 'logging = ' . ($logging ? 'true' : 'false') . PHP_EOL;
    echo 'port = ' . $port . PHP_EOL;
    echo 'directory = ' . $directory . PHP_EOL;
    echo 'double = ' . $double . PHP_EOL;
}
