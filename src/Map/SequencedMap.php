<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

use AccumulatePHP\Accumulation;
use AccumulatePHP\SequencedAccumulation;
use AccumulatePHP\Series\Series;

/**
 * @template TKey
 * @template TValue
 * @extends Map<TKey, TValue>
 * @extends SequencedAccumulation<int, Entry<TKey, TValue>>
 */
interface SequencedMap extends Map, SequencedAccumulation
{
}
