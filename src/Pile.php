<?php

declare(strict_types=1);

namespace AccumulatePHP;

use Countable;
use Iterator;
use OutOfBoundsException;

/**
 * @template TValue
 * @extends Iterator<int, TValue>
 */
interface Pile extends Countable, Iterator {
    /**
     * @return static<TValue>
     */
    public static function empty(): Pile;

    public function isEmpty(): bool;

    /**
     * @return TValue|false
     */
    public function current(): mixed;

    /**
     * @throws OutOfBoundsException if index is out of bounds for pile
     * @throws StringKeyException if the index of a pile is a string
     */
    public function key(): int;
}
