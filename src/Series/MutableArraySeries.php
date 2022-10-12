<?php

declare(strict_types=1);

namespace AccumulatePHP\Series;


use IteratorAggregate;
use JetBrains\PhpStorm\Pure;
use Traversable;

/**
 * @template T
 * @implements MutableSeries<T>
 * @implements IteratorAggregate<int, T>
 */
final class MutableArraySeries implements MutableSeries, IteratorAggregate
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
        $arrayIsList = function (array $array) : bool {
            if (function_exists('array_is_list')) {
                return array_is_list($array);
            }
            if ($array === []) {
                return true;
            }
            $current_key = 0;
            foreach ($array as $key => $noop) {
                if ($key !== $current_key) {
                    return false;
                }
                ++$current_key;
            }
            return true;
        };
        if (!$arrayIsList($array)) {
            $array = array_values($array);
        }
        return new self($array);
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

    #[Pure]
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function getIterator(): Traversable
    {
        foreach ($this->repository as $value) {
            yield $value;
        }
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
