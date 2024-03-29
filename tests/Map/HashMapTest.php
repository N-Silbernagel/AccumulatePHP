<?php

declare(strict_types=1);

namespace Tests\Map;

use AccumulatePHP\Map\Entry;
use AccumulatePHP\Map\HashMap;
use AccumulatePHP\Map\MutableMap;
use AccumulatePHP\Map\UnsupportedKey;
use PHPUnit\Framework\TestCase;
use Tests\AccumulationTestContract;

final class HashMapTest extends TestCase implements AccumulationTestContract, MutableMapTestContract
{
    /** @test */
    public function it_should_allow_creating_empty_instance_via_static_factory(): void
    {
        $emptyMap = HashMap::new();

        self::assertTrue($emptyMap->isEmpty());
    }

    /** @test */
    public function it_should_allow_adding_entries_with_string_keys(): void
    {
        /** @var HashMap<string, int> $hashMap */
        $hashMap = HashMap::new();

        $hashMap->put('abc', 1);

        $value = $hashMap->get('abc');

        self::assertSame(1, $value);
    }


    /** @test */
    public function it_should_allow_adding_entries_with_int_keys(): void
    {
        /** @var HashMap<string, int> $hashMap */
        $hashMap = HashMap::new();

        $hashMap->put('abc', 1);

        $value = $hashMap->get('abc');

        self::assertSame(1, $value);
    }

    /** @test */
    public function it_should_allow_adding_entries_with_object_keys(): void
    {
        $stdClass = (object) [];

        /** @var HashMap<\stdClass, int> $hashMap */
        $hashMap = HashMap::new();

        $hashMap->put($stdClass, 3);

        $value = $hashMap->get($stdClass);

        self::assertSame(3, $value);
    }

    /** @test */
    public function it_should_allow_adding_entries_with_hashable_keys(): void
    {
        $hashable = new EqualHashable('period');

        /** @var HashMap<EqualHashable, int> $hashMap */
        $hashMap = HashMap::new();

        $hashMap->put($hashable, 3);

        $value = $hashMap->get($hashable);

        self::assertSame(3, $value);
    }

    /** @test */
    public function key_with_same_hashcode_should_yield_same_result_if_also_equal(): void
    {
        $equalHashableOne = new EqualHashable('overnight');
        $equalHashableTwo = new EqualHashable('overnight');

        /** @var HashMap<EqualHashable, string> $hashMap */
        $hashMap = HashMap::new();

        $hashMap->put($equalHashableOne, 'gardening');

        $actual = $hashMap->get($equalHashableTwo);
        self::assertSame('gardening', $actual);
    }

    /** @test */
    public function key_with_same_hashcode_should_overwrite_value_if_also_equal(): void
    {
        $equalHashableOne = new EqualHashable('overnight');
        $equalHashableTwo = new EqualHashable('overnight');

        /** @var HashMap<EqualHashable, string> $hashMap */
        $hashMap = HashMap::new();

        $hashMap->put($equalHashableOne, 'gardening');
        $hashMap->put($equalHashableTwo, 'specially');

        $actual = $hashMap->get($equalHashableOne);
        self::assertSame('specially', $actual);
    }

    /** @test */
    public function key_with_same_hashcode_should_should_yield_value_according_to_equals_method(): void
    {
        $unequalHashableOne = new UnequalHashable(1, 2);
        $unequalHashableTwo = new UnequalHashable(1, 3);

        /** @var HashMap<UnequalHashable, string> $hashMap */
        $hashMap = HashMap::new();

        $hashMap->put($unequalHashableOne, 'andorra');
        $hashMap->put($unequalHashableTwo, 'identifier');

        $actualOne = $hashMap->get($unequalHashableOne);
        $actualTwo = $hashMap->get($unequalHashableTwo);
        self::assertSame('andorra', $actualOne);
        self::assertSame('identifier', $actualTwo);
    }

    /** @test */
    public function it_should_return_all_values(): void
    {
        /** @var HashMap<int, string> $hashMap */
        $hashMap = HashMap::new();

        $hashMap->put(1, 'one');
        $hashMap->put(2, 'two');
        $hashMap->put(3, 'three');

        self::assertEquals(['one', 'two', 'three'], $hashMap->values()->toArray());
    }

    /** @test */
    public function it_should_return_all_values_with_same_hash(): void
    {
        $one = new UnequalHashable(5, 1);
        $two = new UnequalHashable(5, 2);

        /** @var HashMap<UnequalHashable, string> $hashMap */
        $hashMap = HashMap::new();

        $hashMap->put($one, 'one');
        $hashMap->put($two, 'two');

        self::assertEquals(['one', 'two'], $hashMap->values()->toArray());
    }

    /** @test */
    public function it_should_return_null_if_a_value_for_the_key_doesnt_exist(): void
    {
        /** @var HashMap<int, int> $hashMap */
        $hashMap = HashMap::new();

        $hashMap->put(1, 1);

        $valueAt2 = $hashMap->get(2);

        self::assertNull($valueAt2);
    }

    /** @test */
    public function it_should_throw_when_given_a_resource_as_key(): void
    {
        $resource = fopen(dirname(__DIR__) . '/teststream.txt', 'r');

        if ($resource === false) {
            self::fail('Could not open teststream file');
        }

        /** @var HashMap<resource, int> $hashMap */
        $hashMap = HashMap::new();

        $this->expectException(UnsupportedKey::class);

        $hashMap->put($resource, 1);
    }

    /** @test */
    public function it_should_keep_correct_count_even_if_a_bucket_has_more_than_1_element(): void
    {
        /** @var HashMap<UnequalHashable, bool> $hashMap */
        $hashMap = HashMap::new();

        $one = new UnequalHashable(1, 1);
        $two = new UnequalHashable(1, 2);

        $hashMap->put($one, true);
        $hashMap->put($two, true);

        self::assertSame(2, $hashMap->count());
    }

    /** @test */
    public function it_should_allow_removing_by_key(): void
    {
        /** @var HashMap<int, bool> $hashMap */
        $hashMap = HashMap::new();

        $hashMap->put(1, true);

        $hashMap->remove(1);

        self::assertTrue($hashMap->isEmpty());
    }

    /** @test */
    public function it_should_be_traversable(): void
    {
        /** @var MutableMap<UnequalHashable, String> $map */
        $map = HashMap::new();

        $one = new UnequalHashable(1, 1);
        $two = new UnequalHashable(1, 2);
        $three = new UnequalHashable(2, 1);

        $map->put($one, 'b');
        $map->put($two, 'a');
        $map->put($three, 'c');

        $actual = [];
        foreach ($map as $item) {
            $actual[] = $item;
        }

        $entryOne = Entry::of($one, 'b');
        $entryTwo = Entry::of($two, 'a');
        $entryThree = Entry::of($three, 'c');

        $expected = [$entryOne, $entryTwo, $entryThree];
        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /** @test */
    public function it_should_be_instantiatable_from_array(): void
    {
        $equalHashable = new EqualHashable('g');
        $hashMap = HashMap::fromArray([
            Entry::of(1, 1),
            Entry::of(2, 'test'),
            Entry::of($equalHashable, $equalHashable)
        ]);

        self::assertSame(1, $hashMap->get(1));
        self::assertSame('test', $hashMap->get(2));
        self::assertSame($equalHashable, $hashMap->get($equalHashable));
    }

    /** @test */
    public function it_should_be_creatable_from_assoc_array(): void
    {
        $map = HashMap::fromAssoc([
            'hello' => 'world',
            'im' => 'dump',
        ]);

        self::assertSame('world', $map->get('hello'));
        self::assertSame('dump', $map->get('im'));
    }

    /** @test */
    public function it_should_be_convertable_to_assoc_array(): void
    {
        /** @var MutableMap<string|EqualHashable, string> $map */
        $map = HashMap::new();

        $map->put('hello', 'world');

        self::assertEquals([
            'hello' => 'world',
        ], $map->toAssoc());
    }

    /** @test */
    public function it_should_ignore_non_scalar_keys_when_converting_to_assoc_array(): void
    {
        /** @var MutableMap<string|EqualHashable, string> $map */
        $map = HashMap::new();

        $hashable = new EqualHashable('test');
        $map->put('hello', 'world');
        $map->put($hashable, 'it');

        self::assertEquals([
            'hello' => 'world',
        ], $map->toAssoc());
    }

    /** @test */
    public function it_should_have_varargs_generator_method(): void
    {
        $map = HashMap::of(Entry::of(1, 1), Entry::of(2, 2));

        self::assertEquals([
            1 => 1,
            2 => 2
        ], $map->toAssoc());
    }

    /** @test */
    public function it_should_be_convertable_to_array(): void
    {
        $inputArray = [Entry::of(4, 4), Entry::of('x', 'y')];
        $hashMap = HashMap::fromArray($inputArray);

        self::assertEquals($inputArray, $hashMap->toArray());
    }

    /** @test */
    public function it_should_support_float_keys(): void
    {
        $hashMap = HashMap::new();
        $hashMap->put(1, false);
        $hashMap->put(1.18, true);

        self::assertSame(true, $hashMap->get(1.18));
    }

    /** @test */
    public function it_should_support_bool_keys(): void
    {
        $hashMap = HashMap::new();
        $hashMap->put(false, true);

        self::assertSame(true, $hashMap->get(false));
    }

    /** @test */
    public function it_should_support_null_keys(): void
    {
        $hashMap = HashMap::new();
        $hashMap->put(null, true);

        self::assertSame(true, $hashMap->get(null));
    }

    /** @test */
    public function it_should_not_support_array_keys(): void
    {
        $this->expectException(UnsupportedKey::class);

        $hashMap = HashMap::new();
        $hashMap->put([], true);
    }

    /** @test */
    public function it_should_return_null_when_trying_to_remove_non_existent_key(): void
    {
        $hashMap = HashMap::new();

        $removeResult = $hashMap->remove('test');

        self::assertNull($removeResult);
    }

    /** @test */
    public function it_should_be_countable(): void
    {
        $hashMap = HashMap::of(Entry::of(5, 10));

        self::assertSame(1, $hashMap->count());

        $hashMap->put(3, 6);

        self::assertSame(2, $hashMap->count());
    }

    /** @test */
    public function it_should_allow_getting_values_as_series(): void
    {
        $hashMap = HashMap::fromAssoc([
            'some' => 'data',
            'in' => 'here'
        ]);

        $values = $hashMap->values();

        self::assertTrue($values->contains('data'));
        self::assertTrue($values->contains('here'));
    }
}
