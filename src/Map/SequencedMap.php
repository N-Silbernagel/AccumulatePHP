<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

/**
 * @template TKey
 * @template TValue
 * @extends SequencedReadonlyMap<TKey, TValue>
 * @extends Map<TKey, TValue>
 */
interface SequencedMap extends Map, SequencedReadonlyMap
{
}
