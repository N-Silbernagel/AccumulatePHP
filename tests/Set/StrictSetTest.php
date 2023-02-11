<?php

declare(strict_types=1);

namespace Tests\Set;

use AccumulatePHP\Set\StrictSet;
use AccumulatePHP\Set\MutableSet;
use AccumulatePHP\Set\Set;
use PHPUnit\Framework\TestCase;
use Tests\AccumulationTestContract;

final class StrictSetTest extends TestCase implements AccumulationTestContract, MutableSetTestContract
{
    /** @test */
    public function it_should_allow_creating_empty_instance_via_static_factory(): void
    {
        $set = StrictSet::new();

        self::assertInstanceOf(StrictSet::class, $set);
        self::assertInstanceOf(Set::class, $set);
        self::assertTrue($set->isEmpty());
    }

    /** @test */
    public function it_should_allow_instantiating_from_array(): void
    {
        /** @var Set<int> $set */
        $set = StrictSet::fromArray([]);

        self::assertInstanceOf(StrictSet::class, $set);
        self::assertInstanceOf(Set::class, $set);
        self::assertTrue($set->isEmpty());
    }

    /** @test */
    public function it_should_not_be_empty_when_instantiating_from_array_with_elements(): void
    {
        /** @var Set<int> $set */
        $set = StrictSet::fromArray([4, 9]);

        self::assertFalse($set->isEmpty());
    }

    /** @test */
    public function it_should_contain_elements_from_array(): void
    {
        /** @var array<int> $data */
        $data = [3, 4];

        $set = StrictSet::fromArray($data);

        self::assertTrue($set->contains(3));
        self::assertTrue($set->contains(4));
    }

    /** @test */
    public function it_should_be_traversable(): void
    {
        /** @var array<string> $data */
        $data = ['hello', 'world'];
        $set = StrictSet::fromArray($data);

        $eachResult = [];
        foreach ($set as $index => $item) {
            $eachResult[$index] = $item;
        }

        self::assertEquals([
            0 => 'hello',
            1 => 'world'
        ], $eachResult);
    }

    /** @test */
    public function it_should_return_false_when_adding_values_that_are_equal_by_type_and_value(): void
    {
        /** @var MutableSet<int|string> $set */
        $set = StrictSet::new();

        $set->add(1);
        $addInt = $set->add(1);

        self::assertFalse($addInt);
        self::assertSame(1, $set->count());
    }

    /** @test */
    public function it_should_return_true_when_adding_values_that_are_equal_by_value_but_not_type(): void
    {
        /** @var MutableSet<int|string> $set */
        $set = StrictSet::new();

        $set->add(1);
        $addString = $set->add('1');

        self::assertTrue($addString);
        self::assertSame(2, $set->count());
    }

    /** @test */
    public function it_should_return_false_when_removing_values_that_didnt_exist(): void
    {
        /** @var MutableSet<int|string> $set */
        $set = StrictSet::new();

        $set->add(1);
        $removeString = $set->remove('1');

        self::assertFalse($removeString);
        self::assertFalse($set->isEmpty());
    }

    /** @test */
    public function it_should_return_true_when_removing_values_that_existed(): void
    {
        /** @var MutableSet<int> $set */
        $set = StrictSet::new();

        $set->add(1);
        $remove = $set->remove(1);

        self::assertTrue($remove);
        self::assertTrue($set->isEmpty());
    }

    /** @test */
    public function contains_should_return_true_if_set_contains_element(): void
    {
        /** @var MutableSet<int> $mutableHashSet */
        $mutableHashSet = StrictSet::new();

        $mutableHashSet->add(77);

        self::assertTrue($mutableHashSet->contains(77));
    }

    /** @test */
    public function contains_should_return_false_if_set_does_not_contain_element(): void
    {
        /** @var MutableSet<int> $mutableHashSet */
        $mutableHashSet = StrictSet::new();

        $mutableHashSet->add(66);

        self::assertFalse($mutableHashSet->contains(54));
    }

    /** @test */
    public function it_should_be_instantiatable_from_array(): void
    {
        $hashSet = StrictSet::fromArray(['me', 'myself']);

        self::assertTrue($hashSet->contains('me'));
        self::assertTrue($hashSet->contains('myself'));
    }

    /** @test */
    public function it_should_have_varargs_generator_method(): void
    {
        $set = StrictSet::of(1, 1, 3);

        self::assertTrue($set->contains(1));
        self::assertTrue($set->contains(3));
    }

    /** @test */
    public function it_should_be_convertable_to_array(): void
    {
        $hashSet = StrictSet::of('x', 'y', 'z');

        self::assertEquals(['x', 'y', 'z'], $hashSet->toArray());
    }

    /** @test */
    public function it_should_keep_count_of_its_contained_elements(): void
    {
        $set = StrictSet::of(1, 2);

        $set->add(3);

        self::assertSame(3, $set->count());
    }

    /** @test */
    public function it_should_be_countable(): void
    {
        $set = StrictSet::of(1, 1, 1, 5);

        self::assertSame(2, $set->count());
    }
}
