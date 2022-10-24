<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

use AccumulatePHP\Accumulation;
use AccumulatePHP\SequencedAccumulation;
use AccumulatePHP\Series\ReadonlySeries;

/**
 * @template TKey
 * @template TValue
 * @extends ReadonlyMap<TKey, TValue>
 * @extends SequencedAccumulation<int, Entry<TKey, TValue>>
 */
interface ReadonlySequencedMap extends ReadonlyMap, SequencedAccumulation
{
}
