<?php

use CleanCode\Args;
use PHPUnit\Framework\TestCase;

class ParseTest extends TestCase
{
    public function testParseBool()
    {
        $arg = new Args('l', ['-l']);

        self::assertEquals(true, $arg->isValid());
        self::assertEquals(1, $arg->cardinality());
        self::assertEquals('', $arg->errorMessage());

        self::assertEquals(true, $arg->getBool('l'));
        self::assertEquals('', $arg->getString('d'));

        self::assertEquals(true, $arg->has('-l'));
        self::assertEquals(false, $arg->has('-d'));
    }

    public function testParseString()
    {
        $arg = new Args('d*', ['-d/tmp']);

        self::assertEquals(true, $arg->isValid());
        self::assertEquals(1, $arg->cardinality());
        self::assertEquals('', $arg->errorMessage());

        self::assertEquals(false, $arg->getBool('l'));
        self::assertEquals('/tmp', $arg->getString('d'));

        self::assertEquals(false, $arg->has('-l'));
        self::assertEquals(true, $arg->has('-d/tmp'));
    }

    public function testParseBoolAndString()
    {
        $arg = new Args('l,d*', ['-l', '-d/tmp']);

        self::assertEquals(true, $arg->isValid());
        self::assertEquals(2, $arg->cardinality());
        self::assertEquals('', $arg->errorMessage());

        self::assertEquals(true, $arg->getBool('l'));
        self::assertEquals('/tmp', $arg->getString('d'));

        self::assertEquals(true, $arg->has('-l'));
        self::assertEquals(true, $arg->has('-d/tmp'));
    }
}

