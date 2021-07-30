<?php

use CleanCode\Args;
use CleanCode\ArgsException;
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
        self::assertEquals(0, $arg->getDouble('f'));

        self::assertEquals(true, $arg->has('-l'));
        self::assertEquals(false, $arg->has('-d'));
        self::assertEquals(false, $arg->has('-p'));
        self::assertEquals(false, $arg->has('-f3.14'));
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
        self::assertEquals(0, $arg->getDouble('f'));

        self::assertEquals(false, $arg->has('-l'));
        self::assertEquals(true, $arg->has('-d/tmp'));
        self::assertEquals(false, $arg->has('-p'));
        self::assertEquals(false, $arg->has('-f3.14'));
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
        self::assertEquals(0, $arg->getDouble('f'));

        self::assertEquals(false, $arg->has('-l'));
        self::assertEquals(false, $arg->has('-d/tmp'));
        self::assertEquals(true, $arg->has('-p80'));
        self::assertEquals(false, $arg->has('-f3.14'));
    }

    public function testParseDouble()
    {
        $arg = new Args('f##', ['-f3.14']);

        self::assertEquals(true, $arg->isValid());
        self::assertEquals(1, $arg->cardinality());
        self::assertEquals('', $arg->errorMessage());

        self::assertEquals(false, $arg->getBoolean('l'));
        self::assertEquals('', $arg->getString('d'));
        self::assertEquals(0, $arg->getInt('p'));
        self::assertEquals(3.14, $arg->getDouble('f'));

        self::assertEquals(false, $arg->has('-l'));
        self::assertEquals(false, $arg->has('-d/tmp'));
        self::assertEquals(false, $arg->has('-p80'));
        self::assertEquals(true, $arg->has('-f3.14'));
    }

    public function testParseBoolStringAndIntAndDouble()
    {
        $arg = new Args('l,d*,p#,f##', ['-l', '-d/tmp', '-p80', '-f3.14']);

        self::assertEquals(true, $arg->isValid());
        self::assertEquals(4, $arg->cardinality());
        self::assertEquals('', $arg->errorMessage());

        self::assertEquals(true, $arg->getBoolean('l'));
        self::assertEquals('/tmp', $arg->getString('d'));
        self::assertEquals(80, $arg->getInt('p'));
        self::assertEquals(3.14, $arg->getDouble('f'));

        self::assertEquals(true, $arg->has('-l'));
        self::assertEquals(true, $arg->has('-d/tmp'));
        self::assertEquals(true, $arg->has('-p80'));
        self::assertEquals(true, $arg->has('-f3.14'));
    }

    public function testParseUnknownElement()
    {
        self::expectException(ArgsException::class);
        new Args('l?', ['-l']);
    }

    public function testParseBadCharacter()
    {
        self::expectException(ArgsException::class);
        new Args('??', ['-l']);
    }
}
