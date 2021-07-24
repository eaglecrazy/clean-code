<?php

namespace CleanCode\ArgumentMarshaler;

use CleanCode\ArgumentMarshaler;

class BooleanArgumentMarshaler extends ArgumentMarshaler
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