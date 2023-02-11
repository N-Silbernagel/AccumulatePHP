<?php

declare(strict_types=1);

namespace AccumulatePHP\Series;

use IteratorAggregate;
use JetBrains\PhpStorm\Pure;
use Traversable;

/**
 * @template T
 * @implements Series<T>
 * @implements IteratorAggregate<int, T>
 */
final class ReadonlyArraySeries implements Series, IteratorAggregate
{
    /**
     * @param MutableSeries<T> $repository
     */
    private function __construct(
        private Series $repository
    )
    {
    }

    /**
     * @return self<T>
     */
    #[Pure]
    public static function new(): self
    {
        /** @var MutableSeries<T> $initSeries */
        $initSeries = ArraySeries::new();
        return new self($initSeries);
    }

    /**
     * @param T... $items
     * @return self<T>
     */
    #[Pure]
    public static function of(...$items): self
    {
        return self::fromArray($items);
    }

    /**
     * @template SeriesT
     * @param Series<SeriesT> $series
     * @return self<SeriesT>
     */
    public static function fromSeries(Series $series): self
    {
        return self::fromArray($series->toArray());
    }

    /**
     * @template ArrayT
     * @param array <ArrayT> $array
     * @return self<ArrayT>
     */
    #[Pure]
    public static function fromArray(array $array): self
    {
        return new self(ArraySeries::fromArray($array));
    }

    public function count(): int
    {
        return $this->repository->count();
    }

    public function isEmpty(): bool
    {
        return $this->repository->isEmpty();
    }

    /**
     * @template ConsumerT
     * @param callable(T): ConsumerT $mapConsumer
     * @return Series<ConsumerT>
     */
    public function map(callable $mapConsumer): Series
    {
        return $this->repository->map($mapConsumer);
    }

    /**
     * @return T
     *
     * @throws IndexOutOfBounds if the index is out of range ( < 0 or >= count())
     */
    public function get(int $index): mixed
    {
        return $this->repository->get($index);
    }

    public function toArray(): array
    {
        return $this->repository->toArray();
    }

    /**
     * @param callable(T): bool $filterConsumer
     * @return ReadonlyArraySeries<T>
     */
    public function filter(callable $filterConsumer): self
    {
        return self::fromSeries($this->repository->filter($filterConsumer));
    }

    public function containsLoose(mixed $element): bool
    {
        return $this->repository->containsLoose($element);
    }

    public function contains(mixed $element): bool
    {
        return $this->repository->contains($element);
    }

    public function find(callable $findConsumer): mixed
    {
        return $this->repository->find($findConsumer);
    }

    public function findIndex(callable $findConsumer): ?int
    {
        return $this->repository->findIndex($findConsumer);
    }

    public function getIterator(): Traversable
    {
        foreach ($this->repository as $value) {
            yield $value;
        }
    }

    public function first(): mixed
    {
        return $this->repository->first();
    }

    public function last(): mixed
    {
        return $this->repository->last();
    }
}
