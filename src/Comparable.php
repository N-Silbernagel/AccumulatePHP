<?php

declare(strict_types=1);

namespace AccumulatePHP;

/**
 * @template T of object
 */
interface Comparable
{
    /**
     * @param T $other
     * @return int 1 when bigger then other 0 when equal -1 when smaller
     */
    public function compareTo(object $other): int;
}
