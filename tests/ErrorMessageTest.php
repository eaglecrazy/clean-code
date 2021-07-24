<?php

use CleanCode\Args;
use PHPUnit\Framework\TestCase;

class ErrorMessageTest extends TestCase
{
    public function testOk()
    {
        $args = ['-l', '-p80', '-d/tmp'];
        $schema = 'l,p#,d*';
        $arg = new Args($schema, $args);

        self::assertEquals(true, $arg->isValid());
        self::assertEquals('', $arg->errorMessage());
    }

    public function testMissingString()
    {
        $args = ['-d'];
        $schema = 'd*';
        $arg = new Args($schema, $args);

        self::assertEquals(false, $arg->isValid());
        self::assertEquals('Could not find string parameter for -d.' . PHP_EOL, $arg->errorMessage());
    }

    public function testMissingInteger()
    {
        $args = ['-p'];
        $schema = 'p#';
        $arg = new Args($schema, $args);

        self::assertEquals(false, $arg->isValid());
        self::assertEquals('Could not find integer parameter for -p.' . PHP_EOL, $arg->errorMessage());
    }

    public function testInvalidInteger()
    {
        $args = ['-px'];
        $schema = 'p#';
        $arg = new Args($schema, $args);

        self::assertEquals(false, $arg->isValid());
        self::assertEquals('Invalid integer parameter: -px.' . PHP_EOL, $arg->errorMessage());
    }

    public function testUnexpectedArgument()
    {
        $args = ['-e'];
        $schema = 'p#';
        $arg = new Args($schema, $args);

        self::assertEquals(false, $arg->isValid());
        self::assertEquals('Argument "-e" unexpected.' . PHP_EOL, $arg->errorMessage());
    }
}
