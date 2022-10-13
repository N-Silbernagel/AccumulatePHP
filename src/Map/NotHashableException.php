<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

use JetBrains\PhpStorm\Pure;
use RuntimeException;

final class NotHashableException extends RuntimeException
{
    #[Pure]
    public function __construct()
    {
        parent::__construct();
    }
}
