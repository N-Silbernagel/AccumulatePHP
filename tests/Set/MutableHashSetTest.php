<?php

declare(strict_types=1);

namespace Tests\Set;

use AccumulatePHP\Set\MutableHashSet;
use AccumulatePHP\Set\MutableSet;
use PHPUnit\Framework\TestCase;
use Tests\AccumulationTestContract;
use Tests\Map\UnequalHashable;

final class MutableHashSetTest extends TestCase implements AccumulationTestContract, SetTestContract
{
    /** @test */
    public function it_should_be_traversable(): void
    {
        /** @var MutableSet<UnequalHashable> $set */
        $set = MutableHashSet::new();

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
        $mutableHashSet = MutableHashSet::new();

        self::assertTrue($mutableHashSet->isEmpty());
        self::assertInstanceOf(MutableHashSet::class, $mutableHashSet);
    }

    /** @test */
    public function contains_should_return_true_if_set_contains_element(): void
    {
        /** @var MutableSet<int> $mutableHashSet */
        $mutableHashSet = MutableHashSet::new();

        $mutableHashSet->add(77);

        self::assertTrue($mutableHashSet->contains(77));
    }

    /** @test */
    public function contains_should_return_false_if_set_does_not_contain_element(): void
    {
        /** @var MutableSet<int> $mutableHashSet */
        $mutableHashSet = MutableHashSet::new();

        $mutableHashSet->add(66);

        self::assertFalse($mutableHashSet->contains(54));
    }
}