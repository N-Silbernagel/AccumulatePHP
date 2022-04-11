<?php

declare(strict_types=1);

namespace DevNilsSilbernagel\Phpile\Series;


use JetBrains\PhpStorm\Pure;

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
    public static function empty(): self
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

    public function toArray(): array
    {
        return $this->repository;
    }
}
