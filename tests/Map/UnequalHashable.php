<?php

declare(strict_types=1);

namespace Tests\Map;

use AccumulatePHP\Hashable;

final class UnequalHashable implements Hashable
{
    public function __construct(
        private readonly int $one,
        private readonly int $two
    )
    {
    }

    public function hashcode(): string
    {
        return (string) $this->one;
    }

    public function equals(object $object): bool
    {
        if ($object === $this) {
            return true;
        }

        if ($this::class !== $object::class) {
            return false;
        }

        /** @var UnequalHashable $object */

        return $object->getOne() === $this->one && $object->getTwo() === $this->two;
    }

    public function getOne(): int
    {
        return $this->one;
    }

    public function getTwo(): int
    {
        return $this->two;
    }
}
