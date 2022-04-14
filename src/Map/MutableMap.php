<?php

declare(strict_types=1);

namespace DevNilsSilbernagel\Phpile\Map;

/**
 * @template TKey of int|string
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
    public function put(int|string $key, mixed $value): mixed;
}
