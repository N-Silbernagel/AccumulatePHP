<?php

declare(strict_types=1);

namespace Tests\Map;

use AccumulatePHP\Map\HashMap;
use AccumulatePHP\Map\UnsupportedHashMapKeyException;
use PHPUnit\Framework\TestCase;

final class HashMapTest extends TestCase
{
    /** @test */
    public function it_should_allow_creating_empty_instance(): void
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
        $resource = fopen(__DIR__ . '/teststream.txt', 'r');

        if ($resource === false) {
            self::fail('Could not open teststream file');
        }

        /** @var HashMap<resource, int> $hashMap */
        $hashMap = HashMap::new();

        $this->expectException(UnsupportedHashMapKeyException::class);

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


}
