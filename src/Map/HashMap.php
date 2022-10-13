<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

use AccumulatePHP\Hashable;
use AccumulatePHP\MixedHash;
use AccumulatePHP\Series\MutableArraySeries;
use AccumulatePHP\Series\Series;
use IteratorAggregate;
use SplDoublyLinkedList;
use Traversable;

/**
 * @template TKey
 * @template TValue
 * @implements MutableMap<TKey, TValue>
 * @implements IteratorAggregate<int, Entry<TKey, TValue>>
 */
final class HashMap implements MutableMap, IteratorAggregate
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
     * @return TValue|null the previous item for the key or null if there was none
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
            /** @var Entry<TKey, TValue> $previousValue */
            $previousValue = $bucket[$bucketIndex];
            $bucket[$bucketIndex] = $entry;
            return $previousValue->getValue();
        }

        $bucket->push($entry);

        $this->size++;
        return null;
    }

    public function remove(mixed $key): mixed
    {
        $hash = $this->evaluateHash($key);

        // TODO refactor duplicate code after tests are present
        if (!array_key_exists($hash, $this->repository)) {
            return null;
        }

        $bucket = $this->repository[$hash];

        $bucketIndex = -1;
        foreach ($bucket as $index => $item) {
            if ($this->keyEquals($item->getKey(), $key)) {
                $bucketIndex = $index;
            }
        }

        if ($bucketIndex === -1) {
            return null;
        }

        /** @var Entry<TKey, TValue> $previousValue */
        $previousValue = $bucket[$bucketIndex];
        unset($bucket[$bucketIndex]);

        if (count($bucket) === 0) {
            unset($this->repository[$hash]);
        }

        $this->size--;
        return $previousValue->getValue();
    }

    /**
     * @throws NotHashableException
     */
    private function evaluateHash(mixed $key): string|int
    {
        return MixedHash::for($key)
            ->computeHash();
    }

    private function keyEquals(mixed $one, mixed $two): bool
    {
        if ($one instanceof Hashable && $two instanceof Hashable) {
            return $one->equals($two);
        }

        return $one === $two;
    }

    public function getIterator(): Traversable
    {
        foreach ($this->repository as $bucket) {
            foreach ($bucket as $entry) {
                yield $entry;
            }
        }
    }

    /**
     * @param array<int, Entry<TKey, TValue>> $array>
     * @return self<TKey, TValue>
     */
    public static function fromArray(array $array): self
    {
        $new = self::new();

        foreach ($array as $entry) {
            $new->put($entry->getKey(), $entry->getValue());
        }

        return $new;
    }

    /**
     * @param array<int|string, TValue> $assocArray
     * @return self<int|string, TValue>
     */
    public static function fromAssoc(array $assocArray): self
    {
        /** @var HashMap<int|string, TValue> $new */
        $new = HashMap::new();

        foreach ($assocArray as $key => $value) {
            $new->put($key, $value);
        }

        return $new;
    }

    public function toAssoc(): array
    {
        /** @var array<int|string, TValue> $array */
        $array = [];
        foreach ($this as $entry) {
            if (!is_scalar($entry->getKey())) {
                continue;
            }
            $array[$entry->getKey()] = $entry->getValue();
        }
        return $array;
    }
}
