<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

use JetBrains\PhpStorm\Pure;
use RuntimeException;
use Throwable;

final class IncomparableKeys extends RuntimeException
{
    #[Pure]
    public function __construct(mixed $first, mixed $second, int $code = 0, ?Throwable $previous = null)
    {

        $firstClass = gettype($first);
        $secondClass = gettype($second);
        parent::__construct("Could not compare $firstClass to $secondClass.", $code, $previous);
    }
}
