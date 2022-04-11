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


}
