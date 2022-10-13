<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

use AccumulatePHP\Accumulation;

/**
 * @template TKey
 * @template TValue
 * @extends Accumulation<int, Entry<TKey, TValue>>
 */
interface Map extends Accumulation
{
    /**
     * @param TKey $key
     * @return TValue|null
     * @throws UnsupportedKeyException If the underlying implementation does not support the type of the given key
     */
    public function get(mixed $key): mixed;

    public function isEmpty(): bool;

    public function count(): int;

    /**
     * @return Accumulation<int, TValue>
     */
    public function values(): Accumulation;

    /**
     * @param array<int|string, TValue> $assocArray
     * @return static<int|string, TValue>
     */
    public static function fromAssoc(array $assocArray): Map;

    /**
     * @return array<int|string, TValue>
     */
    public function toAssoc(): array;
}
