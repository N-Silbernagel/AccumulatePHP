<?php

declare(strict_types=1);

namespace AccumulatePHP\Series;

use AccumulatePHP\Accumulation;

/**
 * @template T
 * @extends Accumulation<int,T>
 */
interface Series extends Accumulation
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

    /** @param T $element */
    public function containsLoose(mixed $element): bool;

    /** @param T $element */
    public function contains(mixed $element): bool;
}
