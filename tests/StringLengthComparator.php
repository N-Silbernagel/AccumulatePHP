<?php

declare(strict_types=1);

namespace Tests;

use AccumulatePHP\Comparator;

/**
 * @implements Comparator<string>
 */
final class StringLengthComparator implements Comparator
{
    public function compare(mixed $first, mixed $second): int
    {
        return strlen($second) <=> strlen($first);
    }
}
