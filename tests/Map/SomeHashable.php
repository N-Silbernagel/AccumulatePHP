<?php

declare(strict_types=1);

namespace Tests\Map;

use AccumulatePHP\Hashable;

final class SomeHashable implements Hashable
{
    private string $value = 'debian';

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

        /** @var SomeHashable $object */

        return $object->getValue() === $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
