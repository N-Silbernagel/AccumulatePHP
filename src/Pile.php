<?php

declare(strict_types=1);

namespace DevNilsSilbernagel\Phpile;

use Countable;

/**
 * @template T
 */
interface Pile extends Countable {
    /**
     * @return static<T>
     */
    public static function empty(): Pile;

    public function count(): int;
}
