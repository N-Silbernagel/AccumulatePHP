<?php

declare(strict_types=1);

namespace Tests;

use AccumulatePHP\Comparable;
use JetBrains\PhpStorm\Pure;

/**
 * @implements Comparable<self>
 */
final class ReverseComparable implements Comparable
{

    private function __construct(
        private int $size
    )
    {
    }

    #[Pure]
    public static function of(int $size): self
    {
        return new self($size);
    }

    public function compareTo(object $other): int
    {
        return ($other->getSize() <=> $this->size) * -1;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
