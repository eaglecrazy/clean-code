<?php

use CleanCode\Args;
use CleanCode\Exceptions\ParseException;
use PHPUnit\Framework\TestCase;

class ParseTest extends TestCase
{
    public function testParseBool()
    {
        $arg = new Args('l', ['-l']);

        self::assertEquals(true, $arg->isValid());
        self::assertEquals(1, $arg->cardinality());
        self::assertEquals('', $arg->errorMessage());

        self::assertEquals(true, $arg->getBoolean('l'));
        self::assertEquals('', $arg->getString('d'));
        self::assertEquals(null, $arg->getInt('p'));

        self::assertEquals(true, $arg->has('-l'));
        self::assertEquals(false, $arg->has('-d'));
        self::assertEquals(false, $arg->has('-p'));
    }

    public function testParseString()
    {
        $arg = new Args('d*', ['-d/tmp']);

        self::assertEquals(true, $arg->isValid());
        self::assertEquals(1, $arg->cardinality());
        self::assertEquals('', $arg->errorMessage());

        self::assertEquals(false, $arg->getBoolean('l'));
        self::assertEquals('/tmp', $arg->getString('d'));
        self::assertEquals(null, $arg->getInt('p'));

        self::assertEquals(false, $arg->has('-l'));
        self::assertEquals(true, $arg->has('-d/tmp'));
        self::assertEquals(false, $arg->has('-p'));
    }

    public function testParseInt()
    {
        $arg = new Args('p#', ['-p80']);

        self::assertEquals(true, $arg->isValid());
        self::assertEquals(1, $arg->cardinality());
        self::assertEquals('', $arg->errorMessage());

        self::assertEquals(false, $arg->getBoolean('l'));
        self::assertEquals('', $arg->getString('d'));
        self::assertEquals(80, $arg->getInt('p'));

        self::assertEquals(false, $arg->has('-l'));
        self::assertEquals(false, $arg->has('-d/tmp'));
        self::assertEquals(true, $arg->has('-p80'));
    }

    public function testParseBoolStringAndInt()
    {
        $arg = new Args('l,d*,p#', ['-l', '-d/tmp', '-p80']);

        self::assertEquals(true, $arg->isValid());
        self::assertEquals(3, $arg->cardinality());
        self::assertEquals('', $arg->errorMessage());

        self::assertEquals(true, $arg->getBoolean('l'));
        self::assertEquals('/tmp', $arg->getString('d'));
        self::assertEquals(80, $arg->getInt('p'));

        self::assertEquals(true, $arg->has('-l'));
        self::assertEquals(true, $arg->has('-d/tmp'));
        self::assertEquals(true, $arg->has('-p80'));
    }

    public function testParseUnknownElement()
    {
        self::expectException(ParseException::class);
        new Args('l?', ['-l']);
    }

    public function testParseBadCharacter()
    {
        self::expectException(ParseException::class);
        new Args('??', ['-l']);
    }
}

