<?php

declare(strict_types=1);

namespace AccumulatePHP\Set;

use AccumulatePHP\Map\HashMap;
use IteratorAggregate;
use JetBrains\PhpStorm\Pure;
use Traversable;

/**
 * @template T
 * @implements MutableSet<T>
 * @implements IteratorAggregate<int, T>
 */
final class HashSet implements MutableSet, IteratorAggregate
{
    /** @param HashMap<T, true> $hashMap */
    private function __construct(
        /** @var HashMap<T, true> $hashMap */
        private HashMap $hashMap
    )
    {
    }

    /**
     * @return self<T>
     */
    #[Pure]
    public static function new(): self
    {
        /** @var HashMap<T, true> $hashMap */
        $hashMap = HashMap::new();
        return new self($hashMap);
    }

    /**
     * @param T ...$items
     * @return self<T>
     */
    public static function of(...$items): self
    {
        return self::fromArray($items);
    }

    #[Pure]
    public function isEmpty(): bool
    {
        return $this->hashMap->isEmpty();
    }

    #[Pure]
    public function count(): int
    {
        return $this->hashMap->count();
    }

    public function add(mixed $element): bool
    {
        $putResult = $this->hashMap->put($element, true);

        return $putResult === null;
    }

    public function remove(mixed $element): bool
    {
        return $this->hashMap->remove($element) !== null;
    }

    public function getIterator(): Traversable
    {
        foreach ($this->hashMap as $item) {
            yield $item->getKey();
        }
    }

    public function contains(mixed $element): bool
    {
        return $this->hashMap->get($element) !== null;
    }

    /**
     * @param T[] $array
     * @return self<T>
     */
    public static function fromArray(array $array): self
    {
        $new = self::new();

        foreach ($array as $item) {
            $new->add($item);
        }

        return $new;
    }

    public function toArray(): array
    {
        return iterator_to_array($this);
    }
}
