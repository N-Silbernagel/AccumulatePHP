<?php

declare(strict_types=1);

namespace AccumulatePHP;

use AccumulatePHP\Map\NotHashableException;

/**
 * @template T
 */
final class MixedHash
{
    /** @param T $element */
    private function __construct(
        /** @var T $element */
        private mixed $element
    )
    {
    }

    /**
     * @param T $element
     * @return self<T>
     */
    public static function for(mixed $element): self
    {
        return new self($element);
    }

    public function computeHash(): string|int
    {
        if ($this->element instanceof Hashable) {
            return $this->element->hashcode();
        }

        if (is_object($this->element)) {
            return spl_object_hash($this->element);
        }

        if (is_int($this->element) || is_string($this->element)) {
            return $this->element;
        }

        throw new NotHashableException();
    }
}
