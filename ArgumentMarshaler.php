<?php

namespace CleanCode;

abstract class ArgumentMarshaler
{
    /**
     * Set a value.
     *
     * @param string $s
     */
    public abstract function set(string $s): void;

    /**
     * Get a value.
     *
     * @return mixed
     */
    public abstract function get();
}
