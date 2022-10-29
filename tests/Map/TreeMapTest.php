<?php

declare(strict_types=1);

namespace Tests\Map;

use AccumulatePHP\Comparable;
use AccumulatePHP\Map\Entry;
use AccumulatePHP\Map\IncomparableKeys;
use AccumulatePHP\Map\TreeMap;
use AccumulatePHP\Map\Map;
use AccumulatePHP\Series\ArraySeries;
use AccumulatePHP\Series\Series;
use PHPUnit\Framework\TestCase;
use Tests\AccumulationTestContract;
use Tests\ReverseComparable;
use Tests\StringLengthComparator;
use function _PHPStan_3bfe2e67c\RingCentral\Psr7\str;

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

        $this->expectException(IncomparableKeys::class);

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

    /** @test */
    public function it_should_compare_by_comparable_(): void
    {
        $zero = ReverseComparable::of(0);
        $one = ReverseComparable::of(1);
        $two = ReverseComparable::of(2);

        $treeMap = TreeMap::new();

        $treeMap->put($zero, 0);
        $treeMap->put($one, 1);
        $treeMap->put($two, 2);

        $values = $treeMap->values();

        self::assertSame(2, $values->get(0));
        self::assertSame(1, $values->get(1));
        self::assertSame(0, $values->get(2));
    }

    /** @test */
    public function it_should_compare_by_comparator(): void
    {
        $stringLengthComparator = new StringLengthComparator();

        /**
         * @var Map<string, true> $map
         */
        $map = TreeMap::comparingBy($stringLengthComparator);

        $map->put('aaa', true);
        $map->put('aa', true);
        $map->put('ba', true);

        $collector = ArraySeries::new();
        foreach ($map as $entry) {
            $collector->add($entry->getKey());
        }

        self::assertSame('aa', $collector->get(0));
        self::assertSame('aaa', $collector->get(1));
        self::assertSame(2, $collector->count());
    }
}
