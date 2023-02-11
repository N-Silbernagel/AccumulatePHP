<?php

declare(strict_types=1);

namespace Tests\Set;

interface MutableSetTestContract
{
    public function contains_should_return_true_if_set_contains_element(): void;

    public function contains_should_return_false_if_set_does_not_contain_element(): void;

    public function it_should_keep_count_of_its_contained_elements(): void;

    public function it_should_return_true_when_removing_values_that_existed(): void;

    public function it_should_return_false_when_removing_values_that_didnt_exist(): void;
}
