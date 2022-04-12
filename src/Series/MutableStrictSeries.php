<?php

declare(strict_types=1);

namespace DevNilsSilbernagel\Phpile\Series;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use PHPStan\Type\ArrayType;

/**
 * @template T
 * @implements MutableSeries<T>
 */
abstract class MutableStrictSeries implements MutableSeries
{
    /**
     * @param MutableArraySeries<T> $repository
     */
    final private function __construct(
        private MutableArraySeries $repository
    )
    {
    }

    /**
     * @return MutableStrictSeries<T>
     */
    #[Pure]
    public static function empty(): MutableStrictSeries
    {
        return new static(MutableArraySeries::empty());
    }

    /**
     * @template ArrayType
     * @param array<ArrayType> $input
     * @return MutableStrictSeries<ArrayType>
     */
    public static function fromArray(array $input): MutableStrictSeries
    {
        foreach ($input as $item) {
            static::checkItemBeforeInsertion($item);
        }

        return new static(MutableArraySeries::fromArray($input));
    }

    /**
     * Create Series without checking via @see MutableStrictSeries::checkItemBeforeInsertion()
     * so implementations need to be sure to only pass in appropriate items.
     * Provides performance benefits through not checking.
     *
     * @template ArrayType
     * @param array<ArrayType> $input
     * @return static
     */
    #[Pure]
    protected static function dangerousFromArray(array $input): static
    {
        return new static(MutableArraySeries::fromArray($input));
    }

    public function count(): int
    {
        return count($this->repository);
    }

    /**
     * @param T $item
     * @throws InvalidArgumentException
     */
    public function add(mixed $item): void
    {
        static::checkItemBeforeInsertion($item);
        $this->repository->add($item);
    }

    /**
     * @template CallableReturnType
     * @param callable(T): CallableReturnType $mapConsumer
     * @return MutableArraySeries<CallableReturnType>
     */
    public function map(callable $mapConsumer): MutableArraySeries
    {
        return $this->repository->map($mapConsumer);
    }

    /**
     * @param callable(T): bool $filterConsumer
     * @return static
     */
    public function filter(callable $filterConsumer): static
    {
        return static::dangerousFromArray($this->repository->filter($filterConsumer)->toArray());
    }

    /**
     * @return T
     */
    public function get(int $index): mixed
    {
        return $this->repository->get($index);
    }

    #[Pure]
    public function toArray(): array
    {
        return $this->repository->toArray();
    }

    /**
     * @throws InvalidArgumentException when given an invalidItem
     */
    abstract protected static function checkItemBeforeInsertion(mixed $item): void;
}
