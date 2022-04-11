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
     * @var array<T>
     */
    private array $repository;

    /**
     * @param array<T> $repository
     */
    final private function __construct(array $repository)
    {
        $this->repository = $repository;
    }

    #[Pure]
    final public static function empty(): static
    {
        return new static([]);
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
        $this->checkItemBeforeInsertion($item);
        $this->repository[] = $item;
    }

    /**
     * @throws InvalidArgumentException when given an invalidItem
     */
    abstract protected function checkItemBeforeInsertion(mixed $item): void;
}
