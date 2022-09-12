<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

/**
 * @template TKey
 * @template TValue
 * @extends Map<TKey, TValue>
 */
interface MutableMap extends Map
{
    /**
     * @param TKey $key
     * @param TValue $value
     * @return TValue
     */
    public function put(mixed $key, mixed $value): mixed;
}
