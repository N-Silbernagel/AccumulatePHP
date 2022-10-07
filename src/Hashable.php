<?php

declare(strict_types=1);

namespace AccumulatePHP;

interface Hashable
{
    public function hashcode(): string|int;

    public function equals(object $object): bool;
}
