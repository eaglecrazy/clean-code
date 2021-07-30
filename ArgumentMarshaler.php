<?php

namespace CleanCode;

use CleanCode\Exceptions\ArgsException;

interface ArgumentMarshaler
{
    /**
     * Set a value.
     *
     * @param string $s
     * @throws ArgsException
     */
    public function set(string $s): void;

    /**
     * Get a value.
     *
     * @return mixed
     */
    public function get();
}
