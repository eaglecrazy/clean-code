<?php

namespace Chapter14;

use CleanCode\Chapter14\Args;
use CleanCode\Chapter14\ArgsException;
use PHPUnit\Framework\TestCase;

class SchemaTest extends TestCase
{
    public function testUsage()
    {
        $arg1 = new Args('l', ['-l']);
        self::assertEquals('-[l]', $arg1->usage());

        $arg2 = new Args('l,d*', ['-l', '-d/tmp']);
        self::assertEquals('-[l,d*]', $arg2->usage());

        $arg3 = new Args('l,d*,p#', ['-l', '-d/tmp', '-p80']);
        self::assertEquals('-[l,d*,p#]', $arg3->usage());

        $arg4 = new Args('l,d*,p#,f##', ['-l', '-d/tmp', '-p80', '-f3.14']);
        self::assertEquals('-[l,d*,p#,f##]', $arg4->usage());
    }

    public function testBadCharacterSchemaElement()
    {
        self::expectException(ArgsException::class);
        new Args("error", ['-1']);
    }

    public function testUnknownSchemaElement()
    {
        self::expectException(ArgsException::class);
        new Args("error", ['-l']);
    }
}