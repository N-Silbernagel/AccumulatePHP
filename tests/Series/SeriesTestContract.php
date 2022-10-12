<?php

declare(strict_types=1);

namespace Tests\Series;

interface SeriesTestContract
{

    /** @test */
    public function find_should_return_null_if_no_match_exists(): void;

    /** @test */
    public function it_should_know_if_it_strictly_contains_element(): void;

    /** @test */
    public function find_should_return_match_if_exists(): void;

    /** @test */
    public function it_should_know_if_it_contains_element(): void;

    /** @test */
    public function it_should_allow_mapping_according_to_a_closure(): void;

    /** @test */
    public function contains_loose_should_be_non_strict(): void;

    /** @test */
    public function contains_should_be_strict(): void;

    /** @test */
    public function it_is_filterable_through_callable(): void;

    /** @test */
    public function it_should_allow_getting_items_by_index(): void;
}
