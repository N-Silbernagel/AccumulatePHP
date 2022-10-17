<?php

declare(strict_types=1);

namespace AccumulatePHP\Series;

/**
 * @template T
 * @extends ReadonlySeries<T>
 */
interface Series extends ReadonlySeries
{
    /**
     * @param T $item
     */
    public function add(mixed $item): void;

    /**
     * @param int $index
     * @return T the removed item
     */
    public function remove(int $index): mixed;
}
