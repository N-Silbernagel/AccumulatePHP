<?php

declare(strict_types=1);

namespace AccumulatePHP;

final class MixedHash
{
    private function __construct(
        private string|int $hash
    )
    {
    }

    public static function for(object|string|int $element): self
    {
        return new self(self::computeHash($element));
    }

    private static function computeHash(object|string|int $element): string|int
    {
        if ($element instanceof Hashable) {
            return $element->hashcode();
        }

        if (is_object($element)) {
            return spl_object_hash($element);
        }

        return $element;
    }

    public function getHash(): int|string
    {
        return $this->hash;
    }
}
