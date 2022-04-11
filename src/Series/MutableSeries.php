<?php

declare(strict_types=1);

namespace DevNilsSilbernagel\Phpile\Series;

/**
 * @template T
 * @extends Series<T>
 */
interface MutableSeries extends Series
{
    /**
     * @param T $item
     */
    public function add(mixed $item): void;
}
