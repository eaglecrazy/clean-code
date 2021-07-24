<?php

namespace CleanCode;

class ArgumentMarshaler
{
    private bool $booleanValue = false;
    private string $stringValue = '';

    /**
     * Set boolean value.
     *
     * @param bool $value
     */
    public function setBoolean(bool $value)
    {
        $this->booleanValue = $value;
    }

    /**
     * Get boolean value.
     *
     * @return bool
     */
    public function getBoolean()
    {
        return $this->booleanValue;
    }

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
}
