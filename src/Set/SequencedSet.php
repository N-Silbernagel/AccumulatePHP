<?php

declare(strict_types=1);

namespace AccumulatePHP\Set;

use AccumulatePHP\SequencedAccumulation;

/**
 * @template T
 * @extends Set<T>
 * @extends SequencedAccumulation<int, T>
 */
interface SequencedSet extends Set, SequencedAccumulation
{

}
