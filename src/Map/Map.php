<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

use AccumulatePHP\Pile;

/**
 * @template TKey of int|string
 * @template TValue
 */
interface Map
{
    /**
     * @param TKey $key
     * @return TValue
     */
    public function get(string|int $key);

    public function isEmpty(): bool;

    public function count(): int;

    /**
     * @return Pile<TValue>
     */
    public function values(): Pile;
}
