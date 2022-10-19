<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

use JetBrains\PhpStorm\Pure;

/**
 * @template TKey
 * @template TValue
 * @implements Entry<TKey, TValue>
 */
final class HashmapEntry implements Entry
{
    /**
     * @param TKey $key
     * @param TValue $value
     */
    private function __construct(
        private mixed $key,
        private mixed $value,
    )
    {
    }

    /**
     * @param TKey $key
     * @param TValue $value
     * @return self<TKey, TValue>
     */
    #[Pure]
    public static function of(mixed $key, mixed $value): self
    {
        return new self($key, $value);
    }

    /**
     * @return TKey
     */
    public function getKey(): mixed
    {
        return $this->key;
    }

    /**
     * @return TValue
     */
    public function getValue(): mixed
    {
        return $this->value;
    }
}