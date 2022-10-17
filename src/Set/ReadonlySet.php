<?php

declare(strict_types=1);

namespace AccumulatePHP\Set;

use AccumulatePHP\Accumulation;

/**
 * @template T
 * @extends Accumulation<int, T>
 */
interface ReadonlySet extends Accumulation
{
    /**
     * @param T $element
     */
    public function contains(mixed $element): bool;
}
