<?php

namespace CleanCode\Chapter14;

class Iterator
{
    private array $array;
    private bool $hasNext = false;

    /**
     * Iterator constructor.
     *
     * @param array $a
     */
    public function __construct(array $a)
    {
        $this->array = $a;

        if ($this->notEmpty()) {
            $this->hasNext = true;
        }
    }

    /**
     * Determine if the iterator has the following item.
     *
     * @return bool
     */
    public function hasNext(): bool
    {
        return $this->hasNext;
    }

    /**
     * Get next item.
     *
     * @return mixed
     */
    public function next()
    {
        $next = next($this->array);

        if (key($this->array) === null) {
            $this->hasNext = false;
        }

        return $next;
    }

    /**
     * Get current item;
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->array);
    }

    /**
     * Check if the iterator has items.
     *
     * @return bool
     */
    public function notEmpty(): bool
    {
        return count($this->array) > 0;
    }
}
