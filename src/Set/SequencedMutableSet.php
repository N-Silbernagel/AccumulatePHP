<?php

declare(strict_types=1);

namespace AccumulatePHP\Set;

use AccumulatePHP\Map\MutableMap;
use AccumulatePHP\Map\SequencedMap;

/**
 * @template T
 * @extends SequencedSet<T>
 * @extends MutableSet<T>
 */
interface SequencedMutableSet extends MutableSet, SequencedSet
{
}
