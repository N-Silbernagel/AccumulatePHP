<?php

declare(strict_types=1);

namespace DevNilsSilbernagel\Phpile\Series;


use JetBrains\PhpStorm\Pure;

/**
 * @template T
 * @implements MutableSeries<T>
 */
final class GenericMutableSeries implements MutableSeries
{
    /**
     * @var array<T> the internal array used for keeping the values
     */
    private array $repository;

    /**
     * @param array<T> $repository
     */
    private function __construct(array $repository)
    {
        $this->repository = $repository;
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
     * @template GivenType
     * @param array<GivenType> $array
     * @return GenericMutableSeries<GivenType>
     */
    #[Pure]
    public static function fromArray(array $array): GenericMutableSeries
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
}
