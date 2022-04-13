<?php

declare(strict_types=1);

namespace DevNilsSilbernagel\Phpile;

/**
 * @template TKey
 * @template TValue
 */
interface Map
{
    /**
     * @param TKey $index
     * @return TValue
     */
    public function get(mixed $index);

    public function isEmpty(): bool;

    /**
     * @param TKey $key
     * @param TValue $value
     * @return TValue
     */
    public function put(mixed $key, mixed $value): mixed;

    public function size(): int;

    /**
     * @return Pile<TValue>
     */
    public function values(): Pile;
}
