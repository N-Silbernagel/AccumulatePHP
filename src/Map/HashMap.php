<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

use AccumulatePHP\Accumulation;
use AccumulatePHP\Hashable;
use AccumulatePHP\Series\DefaultSeries;
use AccumulatePHP\Series\MutableSeries;
use AccumulatePHP\Series\Series;

/**
 * @template TKey
 * @template TValue
 * @implements MutableMap<TKey, TValue>
 */
final class HashMap implements MutableMap
{
    /**
     * @var array<int|string, TValue>
     */
    private array $repository;

    private function __construct()
    {
        $this->repository = [];
    }

    /**
     * @return self<TKey, TValue>
     */
    public static function empty(): self
    {
        return new self();
    }

    /**
     * @throws UnsupportedHashMapKeyException
     */
    public function get(mixed $key)
    {
        $key = $this->evaluateKey($key);

        return $this->repository[$key];
    }

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
    public function values(): Series
    {
        return DefaultSeries::fromArray($this->repository);
    }

    /**
     * @throws UnsupportedHashMapKeyException
     */
    public function put(mixed $key, mixed $value): mixed
    {
        $key = $this->evaluateKey($key);

        $this->repository[$key] = $value;

        return $value;
    }

    /**
     * @throws UnsupportedHashMapKeyException
     */
    private function evaluateKey(mixed $key): string|int
    {
        if ($key instanceof Hashable) {
            return $key->hashcode();
        }

        if (is_object($key)) {
            return spl_object_hash($key);
        }

        if (is_int($key) || is_string($key)) {
            return $key;
        }

        throw new UnsupportedHashMapKeyException();
    }
}
