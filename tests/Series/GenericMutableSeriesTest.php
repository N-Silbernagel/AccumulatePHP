<?php

declare(strict_types=1);

namespace DevNilsSilbernagel\Phpile\Series;

use PHPUnit\Framework\TestCase;

final class GenericMutableSeriesTest extends TestCase
{
    /** @test */
    public function it_should_be_empty_by_default(): void
    {
        $series = GenericMutableSeries::empty();

        self::assertSame(0, $series->count());
    }

    /** @test */
    public function it_should_allow_adding_items(): void
    {
        /**
         * @var GenericMutableSeries<int> $series
         */
        $series = GenericMutableSeries::empty();

        $series->add(1);

        self::assertSame(1, $series->count());
    }

    /** @test */
    public function it_should_allow_removing_items_by_index(): void
    {
        /**
         * @var GenericMutableSeries<int> $series
         */
        $series = GenericMutableSeries::empty();

        $series->add(10);

        $series->remove(0);

        self::assertSame(0, $series->count());
    }

    /** @test */
    public function it_should_return_the_removed_item_when_removing_by_index(): void
    {
        /**
         * @var GenericMutableSeries<int> $series
         */
        $series = GenericMutableSeries::empty();

        $series->add(13);

        $returnValue = $series->remove(0);

        self::assertSame(13, $returnValue);
    }

    /** @test */
    public function it_should_allow_getting_items_by_index(): void
    {
        /**
         * @var GenericMutableSeries<string> $series
         */
        $series = GenericMutableSeries::empty();

        $series->add('test5');
        $series->add('test2');
        $series->add('test');

        $getValue = $series->get(1);

        self::assertSame('test2', $getValue);
    }


    /** @test */
    public function it_should_be_creatable_from_array(): void
    {
        /** @var array<int> $intArray */
        $intArray = [1, 10, 5];

        $fromArray = GenericMutableSeries::fromArray($intArray);

        self::assertSame(3, $fromArray->count());
    }

    /** @test */
    public function it_should_keep_order_of_passed_array(): void
    {
        /** @var array<int> $intArray */
        $intArray = [1, 44542, 2];

        $fromArray = GenericMutableSeries::fromArray($intArray);

        self::assertSame(44542, $fromArray->get(1));
        self::assertSame(1, $fromArray->get(0));
        self::assertSame(2, $fromArray->get(2));
    }
}
