<?php

namespace CleanCode;

use CleanCode\Exceptions\ArgsException;

abstract class ArgumentMarshaler
{
    /**
     * Set a value.
     *
     * @param string $s
     * @throws ArgsException
     */
    public abstract function set(string $s): void;

    /**
     * Get a value.
     *
     * @return mixed
     */
    public abstract function get();
}
