<?php

namespace CleanCode\Chapter15;

include('../../java_functions.php');

class ComparisonCompactor
{
    const ELLIPSIS = "...";
    const DELTA_END = "]";
    const DELTA_START = "[";

    private int $contextLength;
    private string $expected;
    private string $actual;
    private string $compactExpected;
    private string $compactActual;
    private int $prefixLength;
    private int $suffixIndex;

    public function __construct(int $contextLength, string $expected, string $actual)
    {
        $this->contextLength = $contextLength;
        $this->expected = $expected;
        $this->actual = $actual;
    }

    public function formatCompactedComparision(string $message): string
    {
        if ($this->canBeCompacted()) {
            $this->compactExpectedAndActual();

            return $this->format($message, $this->compactExpected, $this->compactActual);
        }

        return $this->format($message, $this->expected, $this->actual);
    }

    private function compactString(string $source): string
    {
        $result = self::DELTA_START . substring($source, $this->prefixLength, strlen($source) - $this->suffixIndex + 1) . self::DELTA_END;

        if ($this->prefixLength > 0)
            $result = $this->computeCommonPrefix() . $result;

        if ($this->suffixIndex > 0)
            $result = $result . $this->computeCommonSuffix();

        return $result;
    }

    private function findCommonPrefix(): void
    {
        $end = min(strlen($this->expected), strlen($this->actual));

        for ($prefixIndex = 0; $prefixIndex < $end; $prefixIndex++) {
            if ($this->expected[$prefixIndex] != $this->actual[$prefixIndex])
                break;
        }

        $this->prefixLength = $prefixIndex;
    }

    private function computeCommonPrefix(): string
    {
        return ($this->prefixLength > $this->contextLength ? self::ELLIPSIS : "")
            . substring(
                $this->expected,
                max(0, $this->prefixLength - $this->contextLength),
                $this->prefixLength
            );
    }

    private function computeCommonSuffix(): string
    {
        $end = min(strlen($this->expected) - $this->suffixIndex + 1 + $this->contextLength, strlen($this->expected));

        return substring(
                $this->expected,
                strlen($this->expected) - $this->suffixIndex + 1,
                $end)
            . (strlen($this->expected) - $this->suffixIndex + 1 < strlen($this->expected) - $this->contextLength ? static::ELLIPSIS : "");

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

    private function canBeCompacted(): bool
    {
        return $this->expected != '' && $this->actual != '' && !$this->areStringsEqual();
    }

    private function compactExpectedAndActual(): void
    {
        $this->findCommonPrefixAndSuffix();

        $this->compactExpected = $this->compactString($this->expected);

        $this->compactActual = $this->compactString($this->actual);
    }

    private function findCommonPrefixAndSuffix(): void
    {
        $this->findCommonPrefix();

        $suffixLength = 1;

        for (; !$this->suffixOverlapsPrefix($suffixLength); $suffixLength++) {
            if ($this->charFromEnd($this->expected, $suffixLength) != $this->charFromEnd($this->actual, $suffixLength))
                break;
        }

        $this->suffixIndex = $suffixLength;
    }

    private function charFromEnd(string $s, int $i): string
    {
        return $s[(strlen($s) - $i)];
    }

    private function suffixOverlapsPrefix(int $suffixLength): bool
    {
        return strlen($this->actual) - $suffixLength < $this->prefixLength
            || strlen($this->expected) - $suffixLength < $this->prefixLength;
    }
}
