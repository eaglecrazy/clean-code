<?php

namespace CleanCode\ArgumentMarshaler;

use CleanCode\ArgumentMarshaler;

class BooleanArgumentMarshaler extends ArgumentMarshaler
{
    /**
     * @inheritDoc
     */
    public function set(string $s): void
    {
        $this->booleanValue = true;
    }
}