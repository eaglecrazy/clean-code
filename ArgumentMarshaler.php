<?php

namespace CleanCode;

abstract class ArgumentMarshaler
{
    private string $stringValue = '';
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
     * Set string value.
     *
     * @param string $value
     */
    public function setString(string $value)
    {
        $this->stringValue = $value;
    }

    /**
     * Get string value.
     *
     * @return bool
     */
    public function getString()
    {
        return $this->stringValue;
    }

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
