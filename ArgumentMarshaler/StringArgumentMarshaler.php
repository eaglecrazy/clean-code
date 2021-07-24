<?php

namespace CleanCode\ArgumentMarshaler;

use CleanCode\ArgumentMarshaler;

class StringArgumentMarshaler extends ArgumentMarshaler
{
    private string $stringValue = '';

    /**
     * @inheritDoc
     */
    public function set(string $s): void
    {
        $this->stringValue = $s;
    }

    /**
     * @inheritDoc
     */
    public function get()
    {
        return $this->stringValue;
    }
}
