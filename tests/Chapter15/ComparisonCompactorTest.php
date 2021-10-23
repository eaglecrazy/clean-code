<?php

namespace Chapter15;

use CleanCode\Chapter15\ComparisonCompactor;
use PHPUnit\Framework\TestCase;

class ComparisonCompactorTest extends TestCase
{
    public function testMessage()
    {
        $failure = (new ComparisonCompactor(0, "b", "c"))->formatCompactedComparision("a");
        self::assertTrue("a expected:<[b]> but was:<[c]>" === $failure);
    }

    public function testStartSame()
    {
        $failure = (new ComparisonCompactor(1, "ba", "bc"))->formatCompactedComparision('');
        self::assertEquals("expected:<b[a]> but was:<b[c]>", $failure);
    }

    public function testEndSame()
    {
        $failure = (new ComparisonCompactor(1, "ab", "cb"))->formatCompactedComparision('');
        self::assertEquals("expected:<[a]b> but was:<[c]b>", $failure);
    }

    public function testSame()
    {
        $failure = (new ComparisonCompactor(1, "ab", "ab"))->formatCompactedComparision('');
        self::assertEquals("expected:<ab> but was:<ab>", $failure);
    }

    public function testNoContextStartAndEndSame()
    {
        $failure = (new ComparisonCompactor(0, "abc", "adc"))->formatCompactedComparision('');
        self::assertEquals("expected:<...[b]...> but was:<...[d]...>", $failure);
    }

    public function testStartAndEndContext()
    {
        $failure = (new ComparisonCompactor(1, "abc", "adc"))->formatCompactedComparision('');
        self::assertEquals("expected:<a[b]c> but was:<a[d]c>", $failure);
    }

    public function testStartAndEndContextWithEllipses()
    {
        $failure = (new ComparisonCompactor(1, "abcde", "abfde"))->formatCompactedComparision('');
        self::assertEquals("expected:<...b[c]d...> but was:<...b[f]d...>", $failure);
    }

    public function testComparisonErrorStartSameComplete()
    {
        $failure = (new ComparisonCompactor(2, "ab", "abc"))->formatCompactedComparision('');
        self::assertEquals("expected:<ab[]> but was:<ab[c]>", $failure);
    }

    public function testComparisonErrorEndSameComplete()
    {
        $failure = (new ComparisonCompactor(0, "bc", "abc"))->formatCompactedComparision('');
        self::assertEquals("expected:<[]...> but was:<[a]...>", $failure);
    }

    public function testComparisonErrorEndSameCompleteContext()
    {
        $failure = (new ComparisonCompactor(2, "bc", "abc"))->formatCompactedComparision('');
        self::assertEquals("expected:<[]bc> but was:<[a]bc>", $failure);
    }

    public function testComparisonErrorOverlapingMatches()
    {
        $failure = (new ComparisonCompactor(0, "abc", "abbc"))->formatCompactedComparision('');
        self::assertEquals("expected:<...[]...> but was:<...[b]...>", $failure);
    }

    public function testComparisonErrorOverlapingMatchesContext()
    {
        $failure = (new ComparisonCompactor(2, "abc", "abbc"))->formatCompactedComparision('');
        self::assertEquals("expected:<ab[]c> but was:<ab[b]c>", $failure);
    }

    public function testComparisonErrorOverlapingMatches2()
    {
        $failure = (new ComparisonCompactor(0, "abcdde", "abcde"))->formatCompactedComparision('');
        self::assertEquals("expected:<...[d]...> but was:<...[]...>", $failure);
    }

    public function testComparisonErrorOverlapingMatches2Context()
    {
        $failure = (new ComparisonCompactor(2, "abcdde", "abcde"))->formatCompactedComparision('');
        self::assertEquals("expected:<...cd[d]e> but was:<...cd[]e>", $failure);
    }

    public function testComparisonErrorWithActualNull()
    {
        $failure = (new ComparisonCompactor(0, "a", ''))->formatCompactedComparision('');
        self::assertEquals("expected:<a> but was:<>", $failure);
    }

    public function testComparisonErrorWithActualNullContext()
    {
        $failure = (new ComparisonCompactor(2, "a", ''))->formatCompactedComparision('');
        self::assertEquals("expected:<a> but was:<>", $failure);
    }

    public function testComparisonErrorWithExpectedNull()
    {
        $failure = (new ComparisonCompactor(0, '', "a"))->formatCompactedComparision('');
        self::assertEquals("expected:<> but was:<a>", $failure);
    }

    public function testComparisonErrorWithExpectedNullContext()
    {
        $failure = (new ComparisonCompactor(2, '', "a"))->formatCompactedComparision('');
        self::assertEquals("expected:<> but was:<a>", $failure);
    }

    public function testBug609972()
    {
        $failure = (new ComparisonCompactor(10, "S&P500", "0"))->formatCompactedComparision('');
        self::assertEquals("expected:<[S&P50]0> but was:<[]0>", $failure);
    }
}
