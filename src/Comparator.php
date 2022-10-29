<?php

declare(strict_types=1);

namespace AccumulatePHP;

/**
 * @template T
 */
interface Comparator
{
    /**
     * @param T $first
     * @param T $second
     * @return int 1 for bigger, 0 for same, -1 for smaller
     */
    public function compare(mixed $first, mixed $second): int;
}
