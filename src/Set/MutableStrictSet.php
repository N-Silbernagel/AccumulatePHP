<?php

declare(strict_types=1);

namespace AccumulatePHP\Set;

use AccumulatePHP\Series\MutableArraySeries;
use AccumulatePHP\Series\MutableSeries;
use IteratorAggregate;
use JetBrains\PhpStorm\Pure;
use Traversable;

/**
 * @template T
 * @implements MutableSet<T>
 * @implements IteratorAggregate<int, T>
 */
final class MutableStrictSet implements MutableSet, IteratorAggregate
{
    /** @var MutableSeries<T>
     * @readonly  */
    private MutableSeries $repository;

    /** @param MutableSeries<T>|null $repository */
    #[Pure]
    private function __construct(?MutableSeries $repository = null)
    {
        $this->repository = $repository ?? MutableArraySeries::new();
    }

    /**
     * @return self<T>
     */
    #[Pure]
    public static function new(): self
    {
        return new self();
    }

    /**
     * @param array<T> $array
     * @return self<T>
     */
    public static function fromArray(array $array): self
    {
        /** @var MutableSeries<T> $encounteredValues */
        $encounteredValues = MutableArraySeries::new();

        foreach ($array as $item) {
            if (!$encounteredValues->contains($item)) {
                $encounteredValues->add($item);
            }
        }

        return new self($encounteredValues);
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
        return $this->count() === 0;
    }

    public function count(): int
    {
        return count($this->repository);
    }

    public function getIterator(): Traversable
    {
        foreach ($this->repository as $item) {
            yield $item;
        }
    }

    /**
     * @param T $element
     */
    public function contains(mixed $element): bool
    {
        return $this->repository->contains($element);
    }

    /**
     * @param T $element
     */
    public function add(mixed $element): bool
    {
        if ($this->contains($element)) {
            return false;
        }

        $this->repository->add($element);
        return true;
    }

    /**
     * @param T $element
     */
    public function remove(mixed $element): bool
    {
        // TODO: refactor to using repository->find
        $searchedIndex = -1;
        foreach ($this->repository as $index => $item) {
            if ($element === $item) {
                $searchedIndex = $index;
            }
        }

        if ($searchedIndex === -1) {
            return false;
        }

        $this->repository->remove($searchedIndex);
        return true;
    }

    public function toArray(): array
    {
        return $this->repository->toArray();
    }
}
