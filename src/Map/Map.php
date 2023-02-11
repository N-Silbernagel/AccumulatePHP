<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

use AccumulatePHP\Accumulation;
use AccumulatePHP\Series\Series;

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
     * @throws UnsupportedKey If the underlying implementation does not support the type of the given key
     */
    public function get(mixed $key): mixed;

    /**
     * @return Series<TValue>
     */
    public function values(): Series;

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
