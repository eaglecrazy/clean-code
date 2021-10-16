<?php

namespace CleanCode\Chapter15;

include('../../java_functions.php');

class ComparisonCompactor
{
    const ELLIPSIS = "...";
    const DELTA_END = "]";
    const DELTA_START = "[";

    private int $fContextLength;
    private string $fExpected;
    private string $fActual;
    private int $fPrefix;
    private int $fSuffix;

    public function __construct(int $contextLength, string $expected, string $actual)
    {
        $this->fContextLength = $contextLength;
        $this->fExpected = $expected;
        $this->fActual = $actual;
    }

    public function compact(string $message): string
    {
        if ($this->fExpected === '' || $this->fActual === '' || $this->areStringsEqual()) {
            return $this->format($message, $this->fExpected, $this->fActual);
        }

        $this->findCommonPrefix();
        $this->findCommonSuffix();

        $expected = $this->compactString($this->fExpected);

        $actual = $this->compactString($this->fActual);

        return $this->format($message, $expected, $actual);
    }

    private function compactString(string $source): string
    {
        $result = self::DELTA_START . substring($source, $this->fPrefix, strlen($source) - $this->fSuffix + 1) . self::DELTA_END;

        if ($this->fPrefix > 0)
            $result = $this->computeCommonPrefix() . $result;

        if ($this->fSuffix > 0)
            $result = $result . $this->computeCommonSuffix();

        return $result;
    }

    private function findCommonPrefix(): void
    {
        $end = min(strlen($this->fExpected), strlen($this->fActual));

        for ($this->fPrefix = 0; $this->fPrefix < $end; $this->fPrefix++) {
            if ($this->fExpected[$this->fPrefix] != $this->fActual[$this->fPrefix])
                break;
        }
    }

    private function findCommonSuffix(): void
    {
        $expectedSuffix = strlen($this->fExpected) - 1;
        $actualSuffix = strlen($this->fActual) - 1;

        for (; $actualSuffix >= $this->fPrefix && $expectedSuffix >= $this->fPrefix; $actualSuffix--, $expectedSuffix--) {
            if ($this->fExpected[$expectedSuffix] != $this->fActual[$actualSuffix])
                break;
        }

        $this->fSuffix = strlen($this->fExpected) - $expectedSuffix;
    }

    private function computeCommonPrefix(): string
    {
        return ($this->fPrefix > $this->fContextLength ? self::ELLIPSIS : "")
            . substring(
                $this->fExpected,
                max(0, $this->fPrefix - $this->fContextLength),
                $this->fPrefix
            );
    }

    private function computeCommonSuffix(): string
    {
        $end = min(strlen($this->fExpected) - $this->fSuffix + 1 + $this->fContextLength, strlen($this->fExpected));

        return substring(
                $this->fExpected,
                strlen($this->fExpected) - $this->fSuffix + 1,
                $end)
            . (strlen($this->fExpected) - $this->fSuffix + 1 < strlen($this->fExpected) - $this->fContextLength ? static::ELLIPSIS : "");

    }

    private function areStringsEqual(): bool
    {
        return $this->fExpected === $this->fActual;
    }

    private function format(string $message, string $expected, string $actual): string
    {
        $message = $message ? $message . ' ' : '';

        return $message . 'expected:<' . $expected . '> but was:<' . $actual . '>';
    }
}
