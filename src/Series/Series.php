<?php

declare(strict_types=1);

namespace DevNilsSilbernagel\Phpile\Series;

use DevNilsSilbernagel\Phpile\Pile;

/**
 * @template T
 * @extends Pile<T>
 */
interface Series extends Pile
{
    /**
     * @template CallableReturnType
     * @param callable(T): CallableReturnType $mapConsumer
     * @return Series<CallableReturnType>
     */
    public function map(callable $mapConsumer): Series;

    /**
     * @return T
     */
    public function get(int $index): mixed;

    /**
     * @return array<T>
     */
    public function toArray(): array;

    /**
     * @param callable(T): bool $filterConsumer
     * @return Series<T>
     */
    public function filter(callable $filterConsumer): Series;
}
