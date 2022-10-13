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

    /**
     * @param TValue ...$items
     * @return static<TValue>
     */
    public static function of(...$items): Accumulation;

    public function isEmpty(): bool;

    /**
     * @return array<TKey, TValue>
     */
    public function toArray(): array;
}
