<?php

declare(strict_types=1);

namespace AccumulatePHP\Series;


use JetBrains\PhpStorm\Pure;
use OutOfBoundsException;

/**
 * @template T
 * @implements MutableSeries<T>
 */
final class MutableArraySeries implements MutableSeries
{
    /**
     * @param array<T> $repository the internal array used for keeping the values
     */
    private function __construct(
        private array $repository)
    {
    }

    /**
     * @return self<T>
     */
    #[Pure]
    public static function new(): self
    {
        return new self([]);
    }

    /**
     * @param T... $items
     * @return self<T>
     */
    #[Pure]
    public static function of(...$items): self
    {
        return new self($items);
    }

    /**
     * @template GivenType
     * @param array<GivenType> $array
     * @return MutableArraySeries<GivenType>
     */
    #[Pure]
    public static function fromArray(array $array): MutableArraySeries
    {
        $arrayValues = array_values($array);
        return new self($arrayValues);
    }

    public function count(): int
    {
        return count($this->repository);
    }

    /**
     * @param T $item
     */
    public function add(mixed $item): void
    {
        $this->repository[] = $item;
    }

    /**
     * @param int $index
     * @return T the removed item
     */
    public function remove(int $index): mixed
    {
        $itemToRemove = $this->repository[$index];
        unset($this->repository[$index]);
        return $itemToRemove;
    }

    /**
     * @return T
     */
    public function get(int $index): mixed
    {
        return $this->repository[$index];
    }

    /**
     * @template CallableReturnType
     * @param callable(T): CallableReturnType $mapConsumer
     * @return self<CallableReturnType>
     */
    public function map(callable $mapConsumer): self
    {
        $mappedRepo = array_map($mapConsumer, $this->repository);
        return self::fromArray($mappedRepo);
    }

    /**
     * @return array<T>
     */
    public function toArray(): array
    {
        return $this->repository;
    }

    /**
     * @param callable(T): bool $filterConsumer
     * @return MutableArraySeries<T>
     */
    public function filter(callable $filterConsumer): MutableArraySeries
    {
        $filteredRepo = array_filter($this->repository, $filterConsumer);
        return self::fromArray($filteredRepo);
    }

    /**
     * @return T|false
     */
    public function current(): mixed
    {
        return current($this->repository);
    }

    public function next(): void
    {
        next($this->repository);
    }

    public function key(): int
    {
        $key = key($this->repository);

        if (is_null($key)) {
            throw new OutOfBoundsException();
        }

        return (int) $key;
    }

    public function valid(): bool
    {
        try {
            $this->key();
            return true;
        } catch (OutOfBoundsException) {
            return false;
        }
    }

    public function rewind(): void
    {
        reset($this->repository);
    }

    #[Pure]
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function containsLoose(mixed $element): bool
    {
        /** @phpstan-ignore-next-line */
        return in_array($element, $this->repository);
    }

    public function contains(mixed $element): bool
    {
        return in_array($element, $this->repository, true);
    }

    public function find(callable $findConsumer): mixed
    {
        $filtered = $this->filter($findConsumer);

        if ($filtered->isEmpty()) {
            return null;
        }

        return $filtered->get(0);
    }
}
