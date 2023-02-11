<?php

declare(strict_types=1);

namespace Tests\Series;

use PHPUnit\Framework\TestCase;
use function AccumulatePHP\Series\mutableSeriesOf;
use function AccumulatePHP\Series\seriesOf;

final class FunctionsTest extends TestCase
{
    /** @test */
    public function mutable_series_of_with_elements(): void
    {
        $mutableSeries = mutableSeriesOf(1,2);
        self::assertSame(1, $mutableSeries->get(0));
        self::assertSame(2, $mutableSeries->get(1));
    }

    /** @test */
    public function series_of_with_elements(): void
    {
        $mutableSeries = seriesOf(1,2);
        self::assertSame(1, $mutableSeries->get(0));
        self::assertSame(2, $mutableSeries->get(1));
    }
}
