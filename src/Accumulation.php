<?php

declare(strict_types=1);

namespace AccumulatePHP;

use Countable;
use Traversable;

/**
 * @template TKey
 * @template TValue
 * @extends Traversable<TKey, TValue>
 */
interface Accumulation extends Countable, Traversable {
    /**
     * @return static<TValue>
     */
    public static function new(): Accumulation;

    public function isEmpty(): bool;
}
