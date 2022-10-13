<?php

declare(strict_types=1);

namespace Tests;


interface AccumulationTestContract
{
    public function it_should_allow_creating_empty_instance_via_static_factory(): void;

    public function it_should_be_traversable(): void;

    public function it_should_be_instantiatable_from_array(): void;

    public function it_should_have_varargs_generator_method(): void;

    public function it_should_be_convertable_to_array(): void;
}
