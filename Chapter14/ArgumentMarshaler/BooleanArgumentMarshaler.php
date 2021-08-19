<?php

namespace CleanCode\Chapter14\ArgumentMarshaler;

use CleanCode\Chapter14\ArgumentMarshaler;

class BooleanArgumentMarshaler implements ArgumentMarshaler
{
    protected bool $booleanValue = false;

    /**
     * @inheritDoc
     */
    public function set(string $s): void
    {
        $this->booleanValue = true;
    }

    /**
     * @inheritDoc
     */
    public function get()
    {
        return $this->booleanValue;
    }
}