<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

/**
 * @template TKey
 * @template TValue
 */
interface Entry
{
    /**
     * @return TKey
     */
    public function getKey(): mixed;

    /**
     * @return TValue
     */
    public function getValue(): mixed;
}
