<?php

class SearchFilterTest extends TestCase
{
    public function testDefaultMethodOnSearchBar()
    {
        // Make the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'search', 'search' => true]);
        $builder->add(['text' => 'Search', 'search' => true, 'method' => 'foo']);
        $builder->add(['text' => 'Search', 'search' => true, 'method' => 'post']);

        // Make assertions.

        $this->assertEquals('get', $builder->menu[0]['method']);
        $this->assertEquals('get', $builder->menu[1]['method']);
        $this->assertEquals('post', $builder->menu[2]['method']);
    }

    public function testDefaultNameOnSearchBar()
    {
        // Make the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'search', 'search' => true]);
        $builder->add(['text' => 'Search', 'search' => true, 'input_name' => 'foo']);

        // Make assertions.

        $this->assertEquals('adminlteSearch', $builder->menu[0]['input_name']);
        $this->assertEquals('foo', $builder->menu[1]['input_name']);
    }
}
