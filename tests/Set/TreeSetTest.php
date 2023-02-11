<?php

declare(strict_types=1);

namespace Tests\Set;

use AccumulatePHP\Set\TreeSet;
use AccumulatePHP\Set\MutableSet;
use PHPUnit\Framework\TestCase;
use Tests\AccumulationTestContract;
use Tests\Map\UnequalHashable;
use function PHPUnit\Framework\assertTrue;

final class TreeSetTest extends TestCase implements AccumulationTestContract, MutableSetTestContract
{
    /** @test */
    public function it_should_be_traversable(): void
    {
        /** @var MutableSet<UnequalHashable> $set */
        $set = TreeSet::new();

        $one = new UnequalHashable(5, 1);
        $two = new UnequalHashable(5, 2);
        $three = new UnequalHashable(10, 1);

        $set->add($one);
        $set->add($two);
        $set->add($three);

        $actual = [];
        foreach ($set as $item) {
            $actual[] = $item;
        }

        $expected = [$one, $two, $three];
        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /** @test */
    public function it_should_allow_creating_empty_instance_via_static_factory(): void
    {
        $mutableTreeSet = TreeSet::new();

        self::assertTrue($mutableTreeSet->isEmpty());
        self::assertInstanceOf(TreeSet::class, $mutableTreeSet);
    }

    /** @test */
    public function contains_should_return_true_if_set_contains_element(): void
    {
        /** @var MutableSet<int> $mutableTreeSet */
        $mutableTreeSet = TreeSet::new();

        $mutableTreeSet->add(77);

        self::assertTrue($mutableTreeSet->contains(77));
    }

    /** @test */
    public function contains_should_return_false_if_set_does_not_contain_element(): void
    {
        /** @var MutableSet<int> $mutableTreeSet */
        $mutableTreeSet = TreeSet::new();

        $mutableTreeSet->add(66);

        self::assertFalse($mutableTreeSet->contains(54));
    }

    /** @test */
    public function it_should_be_instantiatable_from_array(): void
    {
        $treeSet = TreeSet::fromArray(['me', 'myself']);

        self::assertTrue($treeSet->contains('me'));
        self::assertTrue($treeSet->contains('myself'));
    }

    /** @test */
    public function it_should_have_varargs_generator_method(): void
    {
        $set = TreeSet::of(1, 1, 3);

        self::assertTrue($set->contains(1));
        self::assertTrue($set->contains(3));
    }

    /** @test */
    public function it_should_be_convertable_to_array(): void
    {
        $treeSet = TreeSet::of('x', 'y', 'z');

        self::assertEquals(['x', 'y', 'z'], $treeSet->toArray());
    }

    /** @test */
    public function it_should_keep_count_of_its_contained_elements(): void
    {
        $set = TreeSet::of(1, 2);

        $set->add(3);

        self::assertSame(3, $set->count());
    }

    /** @test */
    public function it_should_return_false_when_removing_values_that_didnt_exist(): void
    {
        /** @var MutableSet<int> $set */
        $set = TreeSet::new();

        $set->add(1);
        $removeString = $set->remove(2);

        self::assertFalse($removeString);
        self::assertFalse($set->isEmpty());
    }

    /** @test */
    public function it_should_return_true_when_removing_values_that_existed(): void
    {
        /** @var MutableSet<int> $set */
        $set = TreeSet::new();

        $set->add(1);
        $remove = $set->remove(1);

        self::assertTrue($remove);
        self::assertTrue($set->isEmpty());
    }

    /** @test */
    public function it_should_be_countable(): void
    {
        $set = TreeSet::of('some', 'things');

        self::assertSame(2, $set->count());
    }
}
