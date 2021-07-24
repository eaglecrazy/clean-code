<?php

namespace CleanCode;

class ArgumentMarshaler
{
    private bool $booleanValue = false;

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

}