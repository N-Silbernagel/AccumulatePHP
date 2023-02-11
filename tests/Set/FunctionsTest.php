<?php

declare(strict_types=1);

namespace Tests\Set;

use PHPUnit\Framework\TestCase;
use function AccumulatePHP\Series\mutableSetOf;

final class FunctionsTest extends TestCase
{
    /** @test */
    public function mutable_set_of_with_elements(): void
    {
        $mutableSet = mutableSetOf(1,2);
        self::assertTrue($mutableSet->contains(1));
        self::assertTrue($mutableSet->contains(2));
    }
}
