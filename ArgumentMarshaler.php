<?php

namespace CleanCode;

abstract class ArgumentMarshaler
{
    private int $integerValue = 0;

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

    /**
     * Set integer value.
     *
     * @param int $value
     */
    public function setInteger(int $value)
    {
        $this->integerValue = $value;
    }

    /**
     * Get integer value.
     *
     * @return bool
     */
    public function getInteger()
    {
        return $this->integerValue;
    }
}
