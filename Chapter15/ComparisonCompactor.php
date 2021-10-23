<?php

namespace CleanCode\Chapter15;

include(__DIR__ . '/../java_functions.php');

class ComparisonCompactor
{
    const ELLIPSIS = "...";
    const DELTA_END = "]";
    const DELTA_START = "[";

    private int $contextLength;
    private string $expected;
    private string $actual;
    private int $prefixLength;
    private int $suffixLength;

    public function __construct(int $contextLength, string $expected, string $actual)
    {
        $this->contextLength = $contextLength;
        $this->expected = $expected;
        $this->actual = $actual;
    }

    public function formatCompactedComparision(string $message): string
    {
        $compactExpected = $this->expected;
        $compactActual = $this->actual;

        if ($this->shouldBeCompacted()) {
            $this->findCommonPrefixAndSuffix();

            $compactExpected = $this->compact($this->expected);
            $compactActual   = $this->compact($this->actual);
        }

        return $this->format($message, $compactExpected, $compactActual);
    }

    private function compact(string $s): string
    {
        return $this->startingEllipsis()
            . $this->startingContext()
            . self::DELTA_START
            . $this->delta($s)
            . self::DELTA_END
            . $this->endingContext()
            . $this->endingEllipsis();
    }

    private function findCommonPrefix(): void
    {
        $end = min(strlen($this->expected), strlen($this->actual));

        for ($this->prefixLength = 0; $this->prefixLength < $end; $this->prefixLength++) {
            if ($this->expected[$this->prefixLength] != $this->actual[$this->prefixLength])
                break;
        }
    }

    private function areStringsEqual(): bool
    {
        return $this->expected === $this->actual;
    }

    private function format(string $message, string $expected, string $actual): string
    {
        $message = $message ? $message . ' ' : '';

        return $message . 'expected:<' . $expected . '> but was:<' . $actual . '>';
    }

    private function shouldBeCompacted(): bool
    {
        return !$this->shouldNotBeCompacted();
    }

    private function shouldNotBeCompacted(): bool
    {
        return $this->expected === ''
            || $this->actual === ''
            || $this->areStringsEqual();
    }

    private function findCommonPrefixAndSuffix(): void
    {
        $this->findCommonPrefix();

        for ($this->suffixLength = 0; !$this->suffixOverlapsPrefix(); $this->suffixLength++) {
            if (
                $this->charFromEnd($this->expected, $this->suffixLength)
                != $this->charFromEnd($this->actual, $this->suffixLength)
            )
                break;
        }
    }

    private function charFromEnd(string $s, int $i): string
    {
        return $s[(strlen($s) - $i - 1)];
    }

    private function suffixOverlapsPrefix(): bool
    {
        return strlen($this->actual) - $this->suffixLength <= $this->prefixLength
            || strlen($this->expected) - $this->suffixLength <= $this->prefixLength;
    }

    private function startingEllipsis(): string
    {
        return $this->prefixLength > $this->contextLength ? self::ELLIPSIS : '';
    }

    private function startingContext(): string
    {
        $contextStart = max(0, $this->prefixLength - $this->contextLength);
        $contextEnd = $this->prefixLength;

        return substring($this->expected, $contextStart, $contextEnd);
    }

    private function delta(string $s): string
    {
        $deltaStart = $this->prefixLength;
        $deltaEnd = strlen($s) - $this->suffixLength;

        return substring($s, $deltaStart, $deltaEnd);
    }

    private function endingContext(): string
    {
        $contextStart = strlen($this->expected) - $this->suffixLength;
        $contextEnd = min($contextStart + $this->contextLength, strlen($this->expected));

        return substring($this->expected, $contextStart, $contextEnd);
    }

    private function endingEllipsis(): string
    {
        return $this->suffixLength > $this->contextLength ? self::ELLIPSIS : '';
    }
}
