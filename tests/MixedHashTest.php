<?php

declare(strict_types=1);

namespace Tests;

use AccumulatePHP\MixedHash;
use PHPUnit\Framework\TestCase;
use Tests\Map\EqualHashable;

final class MixedHashTest extends TestCase
{
    /** @test */
    public function it_should_compute_to_given_int(): void
    {
        $mixedHash = MixedHash::for(2294605);

        self::assertSame(2294605, $mixedHash->getHash());
    }

    /** @test */
    public function it_should_compute_to_given_string(): void
    {
        $mixedHash = MixedHash::for('test');

        self::assertSame('test', $mixedHash->getHash());
    }

    /** @test */
    public function it_should_return_object_hash_for_object(): void
    {
        $obj = (object)[];
        $mixedHash = MixedHash::for($obj);

        self::assertSame(spl_object_hash($obj), $mixedHash->getHash());

    }

    /** @test */
    public function it_should_return_hashcode_for_hashable(): void
    {
        $hashable = new EqualHashable('hello');
        $mixedHash = MixedHash::for($hashable);

        self::assertSame($hashable->hashcode(), $mixedHash->getHash());
    }
}
