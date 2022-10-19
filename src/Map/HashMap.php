<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

use AccumulatePHP\Hashable;
use AccumulatePHP\Series\ArraySeries;
use AccumulatePHP\Series\ReadonlySeries;
use IteratorAggregate;
use JetBrains\PhpStorm\Pure;
use SplDoublyLinkedList;
use Traversable;

/**
 * @template TKey
 * @template TValue
 * @implements Map<TKey, TValue>
 * @implements IteratorAggregate<int, Entry<TKey, TValue>>
 */
final class HashMap implements Map, IteratorAggregate
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
     * @param Entry<TKey, TValue> ...$items
     * @return self<TKey, TValue>
     */
    public static function of(...$items): self
    {
        return self::listArrayToHashMap($items);
    }

    /**
     * @param array<int|string, Entry<TKey, TValue>> $array
     * @return self<TKey, TValue>
     */
    private static function listArrayToHashMap(array $array): self
    {
        $new = self::new();

        foreach ($array as $entry) {
            $new->put($entry->getKey(), $entry->getValue());
        }

        return $new;
    }

    /**
     * @return self<TKey, TValue>
     */
    #[Pure]
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

    #[Pure]
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function count(): int
    {
        return $this->size;
    }

    /**
     * @return ReadonlySeries<TValue>
     */
    public function values(): ReadonlySeries
    {
        /** @var ArraySeries<TValue> $series */
        $series = ArraySeries::new();
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
        $entry = HashmapEntry::of($key, $value);

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

    private function evaluateHash(mixed $key): string|int
    {
        // resource are not reliable keys
        // arrays cannot be hashed reliably
        if (is_resource($key) || is_array($key)) {
            throw new UnsupportedKeyException('Unsupported key type.');
        }

        if ($key instanceof Hashable) {
            return $key->hashcode();
        }

        if (is_object($key)) {
            return spl_object_hash($key);
        }

        //map other scalar values to int or string just as array would to avoid notices about implicit float cast

        if (is_float($key)) {
            return (int) $key;
        }

        if (is_null($key)) {
            return '';
        }

        if (is_bool($key)) {
            return (int) $key;
        }

        /** @var int|string $key */

        return $key;
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
        return self::listArrayToHashMap($array);
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

    #[Pure]
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

    public function toArray(): array
    {
        return iterator_to_array($this);
    }
}
