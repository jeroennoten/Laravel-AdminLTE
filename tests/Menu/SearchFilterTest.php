<?php

class SearchFilterTest extends TestCase
{
    public function testDefaultMethodOnSearchBar()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'search', 'search' => true]);
        $builder->add(['text' => 'Search', 'search' => true, 'method' => 'foo']);
        $builder->add(['text' => 'Search', 'search' => true, 'method' => 'post']);

        $this->assertEquals('get', $builder->menu[0]['method']);
        $this->assertEquals('get', $builder->menu[1]['method']);
        $this->assertEquals('post', $builder->menu[2]['method']);
    }

    public function testDefaultNameOnSearchBar()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'search', 'search' => true]);
        $builder->add(['text' => 'Search', 'search' => true, 'input_name' => 'foo']);

        $this->assertEquals('adminlteSearch', $builder->menu[0]['input_name']);
        $this->assertEquals('foo', $builder->menu[1]['input_name']);
    }
}
