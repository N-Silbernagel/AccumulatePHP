<?php

declare(strict_types=1);

namespace Tests\Set;

interface SetTestContract
{
    public function contains_should_return_true_if_set_contains_element(): void;

    public function contains_should_return_false_if_set_does_not_contain_element(): void;
}
