<?php

declare(strict_types=1);

namespace DevNilsSilbernagel\Phpile\Series;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class MutableIntSeriesTest extends TestCase
{
    /** @test */
    public function it_allows_adding_ints(): void
    {
        $intSeries = MutableIntSeries::empty();

        $intSeries->add(9162);

        self::assertSame(1, $intSeries->count());
    }

    /** @test */
    public function it_throws_when_trying_to_add_string(): void
    {
        $intSeries = MutableIntSeries::empty();

        $this->expectException(InvalidArgumentException::class);

        /** @phpstan-ignore-next-line */
        $intSeries->add('test');
    }

    /** @test */
    public function it_can_be_created_from_array(): void
    {
        $fromArray = MutableIntSeries::fromArray([1]);

        self::assertSame(1, $fromArray->count());
    }

    /** @test */
    public function it_is_countable(): void
    {
        $fromArray = MutableIntSeries::fromArray([1]);

        self::assertSame(1, count($fromArray));
    }

    /** @test */
    public function it_throws_when_creating_from_array_with_non_int_items(): void
    {
        $this->expectException(InvalidArgumentException::class);

        MutableIntSeries::fromArray([1, 'wont work']);
    }

    /** @test */
    public function it_can_be_mapped_through_a_callable(): void
    {
        $intSeries = MutableIntSeries::fromArray([19, -69]);

        $mappedSeries = $intSeries->map(fn(int $item) => $item * $item);

        self::assertSame(361, $mappedSeries->get(0));
        self::assertSame(4761, $mappedSeries->get(1));
    }

    /** @test */
    public function it_allows_getting_items_by_index(): void
    {
        $intSeries = MutableIntSeries::fromArray([278, 1563, 20189]);

        self::assertSame(20189, $intSeries->get(2));
    }

    /** @test */
    public function it_can_be_converted_to_array(): void
    {
        $inputArray = [-1923834212];

        $series = MutableIntSeries::fromArray($inputArray);

        self::assertEquals($inputArray, $series->toArray());
    }

    /** @test */
    public function it_has_varargs_generator_method(): void
    {
        $intSeries = MutableIntSeries::of(52, 59593, 5);

        self::assertEquals([
            52,
            59593,
            5
        ], $intSeries->toArray());
    }

    /** @test */
    public function it_is_filterable_through_callable(): void
    {
        $series = MutableIntSeries::of(2, 4, 5);

        $isEven = fn(int $item) => $item % 2 === 0;
        $filteredSeries = $series->filter($isEven);

        self::assertEquals([
            2,
            4
        ], $filteredSeries->toArray());
    }
}
