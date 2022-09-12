<?php

declare(strict_types=1);

namespace AccumulatePHP\Map;

use Exception;

final class UnsupportedHashMapKeyException extends Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}
