<?php

declare(strict_types=1);

namespace AccumulatePHP;

use Countable;
use Iterator;
use OutOfBoundsException;

/**
 * @template TKey
 * @template TValue
 * @extends Iterator<TKey, TValue>
 */
interface Accumulation extends Countable, Iterator {
    /**
     * @return static<TValue>
     */
    public static function new(): Accumulation;

    public function isEmpty(): bool;

    /**
     * @return TValue|false
     */
    public function current(): mixed;

    /**
     * @throws OutOfBoundsException if index is out of bounds for accumulation
     * @return TKey
     */
    public function key(): mixed;
}
