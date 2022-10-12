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
     * @return static<TKey, TValue>
     */
    public static function new(): Accumulation;

    /**
     * @param array<TKey, TValue> $array
     * @return static<TKey, TValue>
     */
    public static function fromArray(array $array): Accumulation;

    public function isEmpty(): bool;
}
