<?php

declare(strict_types=1);

namespace Tests;


interface AccumulationTestContract
{
    /** @test */
    public function it_should_allow_creating_empty_instance_via_static_factory(): void;

    /** @test */
    public function it_should_be_traversable(): void;
}
