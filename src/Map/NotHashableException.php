<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

use RuntimeException;

final class NotHashableException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct();
    }
}
