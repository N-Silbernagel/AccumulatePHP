<?php

declare(strict_types=1);

namespace AccumulatePHP;

/**
 * @template TKey
 * @template TValue
 * @extends Accumulation<TKey, TValue>
 */
interface SequencedAccumulation extends Accumulation
{
    /**
     * @return TValue
     *
     * @throws NoSuchElement if the sequence is empty
     */
    public function first(): mixed;

    /**
     * @return TValue
     *
     * @throws NoSuchElement if the sequence is empty
     */
    public function last(): mixed;
}
