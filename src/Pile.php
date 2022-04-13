<?php

declare(strict_types=1);

namespace DevNilsSilbernagel\Phpile;

use Countable;
use Iterator;

/**
 * @template TValue
 * @extends Iterator<int, TValue>
 */
interface Pile extends Countable, Iterator {
    /**
     * @return static<TValue>
     */
    public static function empty(): Pile;
}
