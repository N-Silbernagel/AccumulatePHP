<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

/**
 * @template TKey
 * @template TValue
 * @extends ReadonlyMap<TKey, TValue>
 */
interface Map extends ReadonlyMap
{
    /**
     * @param TKey $key
     * @param TValue $value
     * @return TValue|null the previous item for the key or null if there was none
     *
     * @throws UnsupportedKeyException If the underlying implementation does not support the type of the given key
     */
    public function put(mixed $key, mixed $value): mixed;

    /**
     * @param TKey $key
     * @return TValue|null the item associated with the key or null if there was none
     *
     * @throws UnsupportedKeyException If the underlying implementation does not support the type of the given key
     */
    public function remove(mixed $key): mixed;
}
