<?php

declare(strict_types=1);

namespace Tests\Set;

use AccumulatePHP\Set\MutableHashSet;
use AccumulatePHP\Set\MutableStrictSet;
use AccumulatePHP\Set\MutableSet;
use AccumulatePHP\Set\Set;
use PHPUnit\Framework\TestCase;
use Tests\AccumulationTestContract;

final class MutableStrictSetTest extends TestCase implements AccumulationTestContract, SetTestContract
{
    /** @test */
    public function it_should_allow_creating_empty_instance_via_static_factory(): void
    {
        $set = MutableStrictSet::new();

        self::assertInstanceOf(MutableStrictSet::class, $set);
        self::assertInstanceOf(Set::class, $set);
        self::assertTrue($set->isEmpty());
    }

    /** @test */
    public function it_should_allow_instantiating_from_array(): void
    {
        /** @var Set<int> $set */
        $set = MutableStrictSet::fromArray([]);

        self::assertInstanceOf(MutableStrictSet::class, $set);
        self::assertInstanceOf(Set::class, $set);
        self::assertTrue($set->isEmpty());
    }

    /** @test */
    public function it_should_not_be_empty_when_instantiating_from_array_with_elements(): void
    {
        /** @var Set<int> $set */
        $set = MutableStrictSet::fromArray([4, 9]);

        self::assertFalse($set->isEmpty());
    }

    /** @test */
    public function it_should_contain_elements_from_array(): void
    {
        /** @var array<int> $data */
        $data = [3, 4];

        $set = MutableStrictSet::fromArray($data);

        self::assertTrue($set->contains(3));
        self::assertTrue($set->contains(4));
    }

    /** @test */
    public function it_should_be_traversable(): void
    {
        /** @var array<string> $data */
        $data = ['hello', 'world'];
        $set = MutableStrictSet::fromArray($data);

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
        $set = MutableStrictSet::new();

        $set->add(1);
        $addInt = $set->add(1);

        self::assertFalse($addInt);
        self::assertSame(1, $set->count());
    }

    /** @test */
    public function it_should_return_true_when_adding_values_that_are_equal_by_value_but_not_type(): void
    {
        /** @var MutableSet<int|string> $set */
        $set = MutableStrictSet::new();

        $set->add(1);
        $addString = $set->add('1');

        self::assertTrue($addString);
        self::assertSame(2, $set->count());
    }

    /** @test */
    public function it_should_return_false_when_removing_values_that_didnt_exist(): void
    {
        /** @var MutableSet<int|string> $set */
        $set = MutableStrictSet::new();

        $set->add(1);
        $removeString = $set->remove('1');

        self::assertFalse($removeString);
        self::assertFalse($set->isEmpty());
    }

    /** @test */
    public function it_should_return_true_when_removing_values_that_existed(): void
    {
        /** @var MutableSet<int> $set */
        $set = MutableStrictSet::new();

        $set->add(1);
        $remove = $set->remove(1);

        self::assertTrue($remove);
        self::assertTrue($set->isEmpty());
    }

    /** @test */
    public function contains_should_return_true_if_set_contains_element(): void
    {
        /** @var MutableSet<int> $mutableHashSet */
        $mutableHashSet = MutableStrictSet::new();

        $mutableHashSet->add(77);

        self::assertTrue($mutableHashSet->contains(77));
    }

    /** @test */
    public function contains_should_return_false_if_set_does_not_contain_element(): void
    {
        /** @var MutableSet<int> $mutableHashSet */
        $mutableHashSet = MutableStrictSet::new();

        $mutableHashSet->add(66);

        self::assertFalse($mutableHashSet->contains(54));
    }

    /** @test */
    public function it_should_be_instantiatable_from_array(): void
    {
        $hashSet = MutableStrictSet::fromArray(['me', 'myself']);

        self::assertTrue($hashSet->contains('me'));
        self::assertTrue($hashSet->contains('myself'));
    }
}
