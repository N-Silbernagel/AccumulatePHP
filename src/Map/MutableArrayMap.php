<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

use AccumulatePHP\Series\DefaultSeries;
use AccumulatePHP\Series\Series;
use JetBrains\PhpStorm\Pure;

/**
 * @template TKey of int|string
 * @template TValue
 * @implements MutableMap<TKey, TValue>
 */
class MutableArrayMap implements MutableMap
{
    /**
     * @param array<TKey, TValue> $repository
     */
    private function __construct(
        private array $repository
    )
    {
    }

    /**
     * @return self<TKey, TValue>
     */
    #[Pure]
    public static function empty(): self
    {
        return new self([]);
    }

    /**
     * @template TArrayKey of int|string
     * @template TArrayValue
     * @param array<TArrayKey, TArrayValue> $from
     * @return self<TArrayKey, TArrayValue>
     */
    #[Pure]
    public static function fromArray(array $from): self
    {
        return new self($from);
    }

    /**
     * @param TKey $key
     * @return TValue
     */
    public function get(mixed $key): mixed
    {
        return $this->repository[$key];
    }

    #[Pure]
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function count(): int
    {
        return count($this->repository);
    }

    /**
     * @return Series<TValue>
     */
    #[Pure]
    public function values(): Series
    {
        return DefaultSeries::fromArray($this->repository);
    }

    /**
     * @param TKey $key
     * @param TValue $value
     * @return TValue
     */
    public function put(mixed $key, mixed $value): mixed
    {
        $this->repository[$key] = $value;
        return $value;
    }
}
