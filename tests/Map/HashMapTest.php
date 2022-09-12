<?php

declare(strict_types=1);

namespace Tests\Map;

use AccumulatePHP\Hashable;
use AccumulatePHP\Map\HashMap;
use AccumulatePHP\Series\DefaultSeries;
use PHPUnit\Framework\TestCase;

final class HashMapTest extends TestCase
{
    /** @test */
    public function it_should_allow_creating_empty_instance(): void
    {
        $emptyMap = HashMap::empty();

        self::assertTrue($emptyMap->isEmpty());
    }

    /** @test */
    public function it_should_allow_adding_entries_with_string_keys(): void
    {
        /** @var HashMap<string, int> $hashMap */
        $hashMap = HashMap::empty();

        $hashMap->put('abc', 1);

        $value = $hashMap->get('abc');

        self::assertSame(1, $value);
    }


    /** @test */
    public function it_should_allow_adding_entries_with_int_keys(): void
    {
        /** @var HashMap<string, int> $hashMap */
        $hashMap = HashMap::empty();

        $hashMap->put('abc', 1);

        $value = $hashMap->get('abc');

        self::assertSame(1, $value);
    }

    /** @test */
    public function it_should_allow_adding_entries_with_object_keys(): void
    {
        $stdClass = (object) [];

        /** @var HashMap<\stdClass, int> $hashMap */
        $hashMap = HashMap::empty();

        $hashMap->put($stdClass, 3);

        $value = $hashMap->get($stdClass);

        self::assertSame(3, $value);
    }

    /** @test */
    public function it_should_allow_adding_entries_with_hashable_keys(): void
    {
        $hashable = new SomeHashable();

        /** @var HashMap<SomeHashable, int> $hashMap */
        $hashMap = HashMap::empty();

        $hashMap->put($hashable, 3);

        $value = $hashMap->get($hashable);

        self::assertSame(3, $value);
    }

    /** @test */
    public function key_with_same_hashcode_should_yield_same_result_if_also_equal(): void
    {
        self::assertTrue(false);
    }

    /** @test */
    public function key_with_same_hashcode_should_yield_colliding_objects_if_not_equal(): void
    {
        self::assertTrue(false);
    }

    /** @test */
    public function it_should_return_all_values(): void
    {
        /** @var HashMap<int, string> $hashMap */
        $hashMap = HashMap::empty();

        $hashMap->put(1, 'one');
        $hashMap->put(2, 'two');
        $hashMap->put(3, 'three');

        $expected = DefaultSeries::fromArray(['one', 'two', 'three']);
        self::assertEquals($expected, $hashMap->values());
    }


}
