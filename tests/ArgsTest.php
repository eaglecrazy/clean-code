<?php

use CleanCode\Args;
use PHPUnit\Framework\TestCase;

class ArgsTest extends TestCase
{
    public function testParseValid()
    {
        $arg = new Args('l', ['-l']);

        self::assertEquals(true, $arg->isValid());
        self::assertEquals(1, $arg->cardinality());
        self::assertEquals('', $arg->errorMessage());
        self::assertEquals(true, $arg->getBool('l'));
    }

    public function testInvalidSchema()
    {
        $arg = new Args("error", ['-l']);
        self::assertEquals(false, $arg->isValid());
        self::assertEquals(0, $arg->cardinality());
        self::assertEquals('Argument(s) - l unexpected.', $arg->errorMessage());

        self::expectError();
        $arg->getBool('l');
    }

    public function testInvalidArgument()
    {
        $arg = new Args('l', ['-e']);

        self::assertEquals(false, $arg->isValid());
        self::assertEquals(0, $arg->cardinality());
        self::assertEquals('Argument(s) - e unexpected.', $arg->errorMessage());
        self::assertEquals(false, $arg->getBool('l'));
    }

    public function testEmpty()
    {
        $arg = new Args('', []);

        self::assertEquals(true, $arg->isValid());
        self::assertEquals(0, $arg->cardinality());
        self::assertEquals('', $arg->errorMessage());
    }

    public function testUsage()
    {
        $arg = new Args('l', ['-l']);
        self::assertEquals('-[l]', $arg->usage());
    }
}

