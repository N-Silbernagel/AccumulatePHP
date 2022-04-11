<?php

declare(strict_types=1);

namespace DevNilsSilbernagel\Phpile\Series;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;

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

    #[Pure]
    final public static function empty(): static
    {
        return new static(MutableArraySeries::empty());
    }

    /**
     * @template ArrayType
     * @param array<ArrayType> $input
     * @return static
     */
    final public static function fromArray(array $input): static
    {
        foreach ($input as $item) {
            static::checkItemBeforeInsertion($item);
        }

        return new static(MutableArraySeries::fromArray($input));
    }

    final public function count(): int
    {
        return count($this->repository);
    }

    /**
     * @param T $item
     * @throws InvalidArgumentException
     */
    final public function add(mixed $item): void
    {
        static::checkItemBeforeInsertion($item);
        $this->repository->add($item);
    }

    /**
     * @template CallableReturnType
     * @param callable(T): CallableReturnType $mapConsumer
     * @return MutableArraySeries<CallableReturnType>
     */
    final public function map(callable $mapConsumer): MutableArraySeries
    {
        return $this->repository->map($mapConsumer);
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
    abstract static protected function checkItemBeforeInsertion(mixed $item): void;
}
