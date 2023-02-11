<?php

declare(strict_types=1);

namespace AccumulatePHP\Series;

use AccumulatePHP\Set\HashSet;
use AccumulatePHP\Set\MutableSet;

if (!function_exists('AccumulatePHP\Series\mutableSetOf')) {
    /**
     * @template T
     * @param T ...$items
     * @return MutableSet<T>
     */
    function mutableSetOf(...$items): MutableSet
    {
        /** @var HashSet<T> */
        return HashSet::fromArray($items);
    }
}
