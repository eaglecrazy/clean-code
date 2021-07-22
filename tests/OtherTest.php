<?php

use CleanCode\Args;
use PHPUnit\Framework\TestCase;

class OtherTest extends TestCase
{
    public function testEmpty()
    {
        $arg = new Args('', []);

        self::assertEquals(true, $arg->isValid());
        self::assertEquals(0, $arg->cardinality());
        self::assertEquals('', $arg->errorMessage());
    }

    public function testInvalidArgument()
    {
        $arg = new Args('l', ['x']);

        self::assertEquals(false, $arg->isValid());
        self::assertEquals(0, $arg->cardinality());
        self::assertEquals('Argument(s) - x unexpected.', $arg->errorMessage());
        self::assertEquals(false, $arg->getBool('l'));
    }
}

