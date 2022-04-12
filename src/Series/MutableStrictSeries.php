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
     * @param T... $items
     * @return MutableStrictSeries<T>
     */
    #[Pure]
    public static function of(...$items): MutableStrictSeries
    {
        return new static(MutableArraySeries::fromArray($items));
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
