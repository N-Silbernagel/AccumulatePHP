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
     */
    public function get(mixed $key): mixed;

    public function isEmpty(): bool;

    public function count(): int;

    /**
     * @return Accumulation<int, TValue>
     */
    public function values(): Accumulation;
}
