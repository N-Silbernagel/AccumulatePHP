<?php

declare(strict_types=1);

namespace AccumulatePHP;

interface Hashable
{
    public function hashcode(): string;

    public function equals(object $object): bool;
}
