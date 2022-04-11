<?php

declare(strict_types=1);

namespace DevNilsSilbernagel\Phpile\Series;

use InvalidArgumentException;

/**
 * @extends MutableStrictSeries<int>
 */
final class MutableIntSeries extends MutableStrictSeries
{
    static protected function checkItemBeforeInsertion(mixed $item): void
    {
        if (!is_int($item)) {
            throw new InvalidArgumentException("Trying to add item of type " . gettype($item) . " to Series of ints.");
        }
    }
}
