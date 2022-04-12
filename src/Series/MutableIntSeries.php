<?php

declare(strict_types=1);

namespace DevNilsSilbernagel\Phpile\Series;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;

/**
 * @extends MutableStrictSeries<int>
 */
final class MutableIntSeries extends MutableStrictSeries
{
    protected static function checkItemBeforeInsertion(mixed $item): void
    {
        if (!is_int($item)) {
            throw new InvalidArgumentException("Trying to add item of type " . gettype($item) . " to Series of ints.");
        }
    }

    #[Pure]
    public static function of(int ...$items): MutableIntSeries
    {
        return self::dangerousFromArray($items);
    }
}
