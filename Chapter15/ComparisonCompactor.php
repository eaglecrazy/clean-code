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
    private int $prefix;
    private int $suffix;

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
        $result = self::DELTA_START . substring($source, $this->prefix, strlen($source) - $this->suffix + 1) . self::DELTA_END;

        if ($this->prefix > 0)
            $result = $this->computeCommonPrefix() . $result;

        if ($this->suffix > 0)
            $result = $result . $this->computeCommonSuffix();

        return $result;
    }

    private function findCommonPrefix(): void
    {
        $end = min(strlen($this->expected), strlen($this->actual));

        for ($this->prefix = 0; $this->prefix < $end; $this->prefix++) {
            if ($this->expected[$this->prefix] != $this->actual[$this->prefix])
                break;
        }
    }

    private function findCommonSuffix(): void
    {
        $expectedSuffix = strlen($this->expected) - 1;
        $actualSuffix = strlen($this->actual) - 1;

        for (; $actualSuffix >= $this->prefix && $expectedSuffix >= $this->prefix; $actualSuffix--, $expectedSuffix--) {
            if ($this->expected[$expectedSuffix] != $this->actual[$actualSuffix])
                break;
        }

        $this->suffix = strlen($this->expected) - $expectedSuffix;
    }

    private function computeCommonPrefix(): string
    {
        return ($this->prefix > $this->contextLength ? self::ELLIPSIS : "")
            . substring(
                $this->expected,
                max(0, $this->prefix - $this->contextLength),
                $this->prefix
            );
    }

    private function computeCommonSuffix(): string
    {
        $end = min(strlen($this->expected) - $this->suffix + 1 + $this->contextLength, strlen($this->expected));

        return substring(
                $this->expected,
                strlen($this->expected) - $this->suffix + 1,
                $end)
            . (strlen($this->expected) - $this->suffix + 1 < strlen($this->expected) - $this->contextLength ? static::ELLIPSIS : "");

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
        $this->findCommonPrefix();
        $this->findCommonSuffix();

        $this->compactExpected = $this->compactString($this->expected);

        $this->compactActual = $this->compactString($this->actual);
    }
}
