<?php

declare(strict_types=1);

namespace PHPile\Series;

use JetBrains\PhpStorm\Pure;

/**
 * @template T
 * @implements Series<T>
 */
class DefaultSeries implements Series
{
    /**
     * @param Series<T> $repository
     */
    private function __construct(
        private readonly Series $repository
    )
    {
    }

    /**
     * @return self<T>
     */
    #[Pure]
    public static function empty(): self
    {
        /** @var Series<T> $initSeries */
        $initSeries = MutableArraySeries::empty();
        return new self($initSeries);
    }

    /**
     * @template SeriesT
     * @param Series<SeriesT> $series
     * @return self<SeriesT>
     */
    #[Pure]
    public static function fromSeries(Series $series): self
    {
        return new self($series);
    }

    /**
     * @template ArrayT
     * @param array <ArrayT> $array
     * @return self<ArrayT>
     */
    #[Pure]
    public static function fromArray(array $array): self
    {
        return new self(MutableArraySeries::fromArray($array));
    }

    public function next(): void
    {
        $this->repository->next();
    }

    public function valid(): bool
    {
        return $this->repository->valid();
    }

    public function rewind(): void
    {
        $this->repository->rewind();
    }

    public function count(): int
    {
        return $this->repository->count();
    }

    public function isEmpty(): bool
    {
        return $this->repository->isEmpty();
    }

    public function current(): mixed
    {
        return $this->repository->current();
    }

    public function key(): int
    {
        return $this->repository->key();
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

    public function get(int $index): mixed
    {
        return $this->repository->get(0);
    }

    public function toArray(): array
    {
        return $this->repository->toArray();
    }

    /**
     * @param callable(T): bool $filterConsumer
     * @return DefaultSeries<T>
     */
    public function filter(callable $filterConsumer): self
    {
        return self::fromSeries($this->repository->filter($filterConsumer));
    }
}
