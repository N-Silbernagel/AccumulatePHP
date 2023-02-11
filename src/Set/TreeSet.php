<?php

declare(strict_types=1);

namespace AccumulatePHP\Set;

use AccumulatePHP\Map\TreeMap;
use IteratorAggregate;
use JetBrains\PhpStorm\Pure;
use Traversable;

/**
 * @template T
 * @implements MutableSet<T>
 * @implements IteratorAggregate<int, T>
 */
final class TreeSet implements MutableSet, IteratorAggregate
{
    /** @param TreeMap<T, true> $treeMap */
    private function __construct(
        /** @var TreeMap<T, true> $treeMap */
        private TreeMap $treeMap
    )
    {
    }

    /**
     * @return self<T>
     */
    #[Pure]
    public static function new(): self
    {
        /** @var TreeMap<T, true> $treeMap */
        $treeMap = TreeMap::new();
        return new self($treeMap);
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
        return $this->treeMap->isEmpty();
    }

    #[Pure]
    public function count(): int
    {
        return $this->treeMap->count();
    }

    public function add(mixed $element): bool
    {
        $putResult = $this->treeMap->put($element, true);

        return $putResult === null;
    }

    public function remove(mixed $element): bool
    {
        return $this->treeMap->remove($element) !== null;
    }

    public function getIterator(): Traversable
    {
        foreach ($this->treeMap as $item) {
            yield $item->getKey();
        }
    }

    public function contains(mixed $element): bool
    {
        return $this->treeMap->get($element) !== null;
    }

    /**
     * @param T[] $array>
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
