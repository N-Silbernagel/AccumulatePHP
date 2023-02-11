<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

/**
 * @template TKey
 * @template TValue
 * @extends SequencedMap<TKey, TValue>
 * @extends MutableMap<TKey, TValue>
 */
interface SequencedMutableMap extends MutableMap, SequencedMap
{
}
