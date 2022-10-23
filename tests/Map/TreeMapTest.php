<?php

declare(strict_types=1);

namespace Tests\Map;

use AccumulatePHP\Map\Entry;
use AccumulatePHP\Map\IncomparableKeysException;
use AccumulatePHP\Map\TreeMap;
use AccumulatePHP\Map\Map;
use PHPUnit\Framework\TestCase;
use Tests\AccumulationTestContract;

final class TreeMapTest extends TestCase implements MapTestContract, AccumulationTestContract
{
    /** @test */
    public function it_should_be_creatable_from_assoc_array(): void
    {
        $input = [
            'this' => 'is',
        ];

        $map = TreeMap::fromAssoc($input);

        self::assertSame('is', $map->get('this'));
    }

    /** @test */
    public function it_should_throw_when_object_and_string_keys_are_used(): void
    {
        $treeMap = TreeMap::new();

        $key = new EqualHashable('hi');
        $treeMap->put($key, 1);

        $this->expectException(IncomparableKeysException::class);

        $treeMap->put('hello there', 2);
    }

    /** @test */
    public function it_should_be_convertable_to_assoc_array(): void
    {
        $treeMap = TreeMap::new();

        $treeMap->put('hello there', 2);
        $treeMap->put(1, 3);

        $expected = [
            'hello there' => 2,
            1 => 3
        ];

        self::assertEquals($expected, $treeMap->toAssoc());
    }

    /** @test */
    public function it_should_ignore_non_scalar_keys_when_converting_to_assoc_array(): void
    {
        $key = (object) [];
        $treeMap = TreeMap::new();

        $treeMap->put($key, true);

        self::assertEquals([], $treeMap->toAssoc());
    }

    /** @test */
    public function it_should_return_null_when_trying_to_remove_non_existent_key(): void
    {
        $map = TreeMap::new();

        $map->put('test', true);
        $result = $map->remove('nope');

        self::assertNull($result);
    }

    /** @test */
    public function it_should_allow_creating_empty_instance_via_static_factory(): void
    {
        $map = TreeMap::new();

        self::assertInstanceOf(TreeMap::class, $map);
        self::assertTrue($map->isEmpty());
    }

    /** @test */
    public function it_should_be_traversable(): void
    {
        $treeMap = TreeMap::fromAssoc([
            2 => true,
            1 => true,
            3 => true,
            4 => true,
        ]);

        $collected = [];

        foreach ($treeMap as $entry) {
            $collected[] = $entry;
        }

        $entryTwo = Entry::of(2, true);
        $entryOne = Entry::of(1, true);
        $entryThree = Entry::of(3, true);
        $entryFour = Entry::of(4, true);

        $expected = [
            $entryOne,
            $entryTwo,
            $entryThree,
            $entryFour
        ];
        self::assertEquals($expected, $collected);
    }

    /** @test */
    public function it_should_be_instantiatable_from_array(): void
    {
        $treeMapEntryOne = Entry::of('hi', 8);
        $treeMapEntryTwo = Entry::of('world', 16);

        $map = TreeMap::fromArray([$treeMapEntryOne, $treeMapEntryTwo]);

        self::assertSame(8, $map->get('hi'));
        self::assertSame(16, $map->get('world'));
    }

    /** @test */
    public function it_should_have_varargs_generator_method(): void
    {
        $treeMapEntryOne = Entry::of('hi', 8);
        $treeMapEntryTwo = Entry::of('world', 9);

        $map = TreeMap::of($treeMapEntryOne, $treeMapEntryTwo);

        self::assertSame(8, $map->get('hi'));
        self::assertSame(9, $map->get('world'));
    }

    /** @test */
    public function it_should_be_convertable_to_array(): void
    {
        $treeMap = TreeMap::new();

        $treeMap->put('test', 'me');
        $treeMap->put('real', 'good');

        $entryTwo = Entry::of('test', 'me');
        $entryOne = Entry::of('real', 'good');

        $expected = [
            $entryOne,
            $entryTwo,
        ];
        self::assertEquals($expected, $treeMap->toArray());
    }

    /** @test */
    public function it_should_allow_putting_entries_in(): void
    {
        /** @var Map<int, int> $treeMap */
        $treeMap = TreeMap::new();

        $treeMap->put(1, 2);

        self::assertSame(2, $treeMap->get(1));
    }

    /** @test */
    public function it_should_be_countable(): void
    {
        $treeMap = TreeMap::new();

        self::assertSame(0, $treeMap->count());

        $treeMap->put(1, 1);

        self::assertSame(1, $treeMap->count());

        $treeMap->put(1, 2);

        self::assertSame(1, $treeMap->count());

        $treeMap->remove(1);

        self::assertSame(0, $treeMap->count());
    }

    /** @test */
    public function it_should_allow_getting_values_as_series(): void
    {
        $treeMap = TreeMap::fromAssoc([
            'test' => 'me',
            'right' => 'now',
        ]);

        $values = $treeMap->values();

        self::assertSame('now', $values->get(0));
        self::assertSame('me', $values->get(1));
    }

    /** @test */
    public function it_should_allow_replacing_the_root_node(): void
    {
        $treeMap = TreeMap::of(Entry::of(1, false));

        $put = $treeMap->put(1, true);

        self::assertFalse($put);
        self::assertTrue($treeMap->get(1));
    }

    /** @test */
    public function it_should_allow_removing_the_root_node(): void
    {
        $treeMap = TreeMap::of(
            Entry::of(1, true),
            Entry::of(2, true),
            Entry::of(0, true),
        );

        $treeMap->remove(1);

        self::assertSame(2, $treeMap->count());
        self::assertNull($treeMap->get(1));
        self::assertTrue($treeMap->get(2));
        self::assertTrue($treeMap->get(0));
    }

    /** @test */
    public function subtree_elements_should_be_kept_when_removing_elements(): void
    {
        $treeMap = TreeMap::of(
            Entry::of(3, true),
            Entry::of(1, true),
            Entry::of(2, true),
            Entry::of(0, true),
            Entry::of(5, true),
            Entry::of(4, true),
            Entry::of(6, true),
        );

        $treeMap->remove(1);

        self::assertNull($treeMap->get(1));
        self::assertTrue($treeMap->get(2));
        self::assertTrue($treeMap->get(0));

        $treeMap->remove(5);

        self::assertNull($treeMap->get(5));
        self::assertTrue($treeMap->get(4));
        self::assertTrue($treeMap->get(6));
    }
}
