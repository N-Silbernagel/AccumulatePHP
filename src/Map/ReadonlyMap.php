<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

use AccumulatePHP\Accumulation;
use AccumulatePHP\Series\ReadonlySeries;

/**
 * @template TKey
 * @template TValue
 * @extends Accumulation<int, Entry<TKey, TValue>>
 */
interface ReadonlyMap extends Accumulation
{
    /**
     * @param TKey $key
     * @return TValue|null
     * @throws UnsupportedKey If the underlying implementation does not support the type of the given key
     */
    public function get(mixed $key): mixed;

    /**
     * @return ReadonlySeries<TValue>
     */
    public function values(): ReadonlySeries;

    /**
     * @param array<int|string, TValue> $assocArray
     * @return static<int|string, TValue>
     */
    public static function fromAssoc(array $assocArray): ReadonlyMap;

    /**
     * @return array<int|string, TValue>
     */
    public function toAssoc(): array;
}
