<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

use AccumulatePHP\Hashable;
use AccumulatePHP\Series\MutableArraySeries;
use AccumulatePHP\Series\Series;
use SplDoublyLinkedList;

/**
 * @template TKey
 * @template TValue
 * @implements MutableMap<TKey, TValue>
 */
final class HashMap implements MutableMap
{
    private int $size;
    /**
     * @var array<int|string, SplDoublyLinkedList<Entry<TKey, TValue>>>
     */
    private array $repository;

    private function __construct()
    {
        $this->size = 0;
        $this->repository = [];
    }

    /**
     * @return self<TKey, TValue>
     */
    public static function new(): self
    {
        return new self();
    }

    /**
     * @throws UnsupportedHashMapKeyException
     */
    public function get(mixed $key): mixed
    {
        $hash = $this->evaluateHash($key);

        $bucket = $this->repository[$hash] ?? new SplDoublyLinkedList();

        foreach ($bucket as $entry) {
            if ($this->keyEquals($entry->getKey(), $key)) {
                return $entry->getValue();
            }
        }

        return null;
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function count(): int
    {
        return $this->size;
    }

    /**
     * @return Series<TValue>
     */
    public function values(): Series
    {
        /** @var MutableArraySeries<TValue> $series */
        $series = MutableArraySeries::new();
        foreach ($this->repository as $bucket) {
            foreach ($bucket as $entry) {
                $series->add($entry->getValue());
            }
        }
        return $series;
    }

    /**
     * @param TKey $key
     * @param TValue $value
     * @throws UnsupportedHashMapKeyException
     */
    public function put(mixed $key, mixed $value): mixed
    {
        $hash = $this->evaluateHash($key);

        if (!array_key_exists($hash, $this->repository)) {
            $this->repository[$hash] = new SplDoublyLinkedList();
        }


        $bucket = $this->repository[$hash];

        $bucketIndex = -1;
        foreach ($bucket as $index => $item) {
            if ($this->keyEquals($item->getKey(), $key)) {
                $bucketIndex = $index;
            }
        }


        /** @var Entry<TKey, TValue> $entry */
        $entry = Entry::of($key, $value);


        if ($bucketIndex !== -1) {
            $bucket[$bucketIndex] = $entry;
            return $value;
        }

        $bucket->push($entry);

        $this->size++;
        return $value;
    }

    /**
     * @throws UnsupportedHashMapKeyException
     */
    private function evaluateHash(mixed $key): string|int
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

    private function keyEquals(mixed $one, mixed $two): bool
    {
        if ($one instanceof Hashable && $two instanceof Hashable) {
            return $one->equals($two);
        }

        return $one === $two;
    }
}
