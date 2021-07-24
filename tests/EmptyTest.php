<?php

use CleanCode\Args;
use PHPUnit\Framework\TestCase;

class EmptyTest extends TestCase
{
    public function testEmpty()
    {
        $arg = new Args('', []);

        self::assertEquals(true, $arg->isValid());
        self::assertEquals(0, $arg->cardinality());
        self::assertEquals('', $arg->errorMessage());
    }
}
