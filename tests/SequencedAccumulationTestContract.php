<?php

declare(strict_types=1);

namespace Tests;

interface SequencedAccumulationTestContract
{
    public function it_should_return_first_element(): void;
    public function it_should_throw_if_no_first_element_exists(): void;
    public function it_should_throw_if_no_last_element_exists(): void;

    public function it_should_return_last_element(): void;
}
