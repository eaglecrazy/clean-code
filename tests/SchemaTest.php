<?php

use CleanCode\Args;
use CleanCode\Exceptions\ParseException;
use PHPUnit\Framework\TestCase;

class SchemaTest extends TestCase
{
    public function testUsage()
    {
        $arg1 = new Args('l', ['-l']);
        self::assertEquals('-[l]', $arg1->usage());

        $arg2 = new Args('l,d*', ['-l', '-d/tmp']);
        self::assertEquals('-[l,d*]', $arg2->usage());
    }

    public function testBadCharacterSchemaElement()
    {
        self::expectException(ParseException::class);
        new Args("error", ['-1']);
    }

    public function testUnknownSchemaElement()
    {
        self::expectException(ParseException::class);
        new Args("error", ['-l']);
    }
}