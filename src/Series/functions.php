<?php

declare(strict_types=1);

namespace AccumulatePHP\Series;

use JetBrains\PhpStorm\Pure;

if (!function_exists('AccumulatePHP\Series\mutableSeriesOf')) {
    /**
     * @template T
     * @param T ...$items
     * @return MutableSeries<T>
     */
    #[Pure]
    function mutableSeriesOf(...$items): MutableSeries
    {
        return ArraySeries::fromArray($items);
    }
}


if (!function_exists('AccumulatePHP\Series\seriesOf')) {
    /**
     * @template T
     * @param T ...$items
     * @return Series<T>
     */
    #[Pure]
    function seriesOf(...$items): Series
    {
        return ReadonlyArraySeries::fromArray($items);
    }
}
