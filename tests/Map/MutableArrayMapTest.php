<?php

declare(strict_types=1);

namespace Map;

use DevNilsSilbernagel\Phpile\Map\MutableArrayMap;
use PHPUnit\Framework\TestCase;

class MutableArrayMapTest extends TestCase
{
    /** @test */
    public function it_allows_creating_an_empty_instance(): void
    {
        $map = MutableArrayMap::empty();

        self::assertSame(0, $map->count());
        self::assertTrue($map->isEmpty());
    }

    /** @test */
    public function it_allows_creating_from_array(): void
    {
        $fromArray = MutableArrayMap::fromArray([1, 2]);

        self::assertSame(1, $fromArray->get(0));
    }


    /** @test */
    public function it_allows_putting_values_by_key(): void
    {
        $map = MutableArrayMap::empty();

        $map->put('test', 123);

        self::assertSame(123, $map->get('test'));
        self::assertSame(1, $map->count());
        self::assertFalse($map->isEmpty());
    }

    /** @test */
    public function it_returns_its_values_as_a_series(): void
    {
        $map = MutableArrayMap::empty();

        $map->put('test', 1);
        $map->put('hair', 29261);

        $values = $map->values();

        self::assertEquals([1, 29261], $values->toArray());
    }


}
