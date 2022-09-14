<?php

declare(strict_types=1);

namespace Tests\Map;

use AccumulatePHP\Hashable;

final class EqualHashable implements Hashable
{
    public function __construct(
        private readonly string $value
    )
    {
    }

    public function hashcode(): string
    {
        return $this->value;
    }

    public function equals(object $object): bool
    {
        if ($object === $this) {
            return true;
        }

        if ($this::class !== $object::class) {
            return false;
        }

        /** @var EqualHashable $object */

        return $object->getValue() === $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
