<?php

declare(strict_types=1);

namespace DevNilsSilbernagel\Phpile\Series;

use DevNilsSilbernagel\Phpile\Pile;
use JetBrains\PhpStorm\Pure;

/**
 * @template T
 * @extends Pile<T>
 */
interface Series extends Pile
{
    /**
     * @param T... $items
     * @return self<T>
     */
    public static function of(...$items): Pile;

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
}
