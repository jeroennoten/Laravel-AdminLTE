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

    public function testDefaultsOnEverySearchBarType()
    {
        // Make the menu with all the supported search box definitions.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Search', 'search' => true]);
        $builder->add(['text' => 'Search', 'type' => 'navbar-search']);
        $builder->add(['text' => 'Search', 'type' => 'sidebar-custom-search']);
        $builder->add(['text' => 'Search', 'type' => 'sidebar-menu-search']);

        // Make assertions.

        foreach ($builder->menu as $idx => $item) {
            $this->assertEquals('get', $item['method'], "Item {$idx}");
            $this->assertEquals('adminlteSearch', $item['input_name'], "Item {$idx}");
        }
    }

    public function testTheMethodIsCaseInsensitive()
    {
        // Make the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Search', 'search' => true, 'method' => 'POST']);
        $builder->add(['text' => 'Search', 'search' => true, 'method' => 'Get']);

        // Make assertions. Note the configured value is preserved.

        $this->assertEquals('POST', $builder->menu[0]['method']);
        $this->assertEquals('Get', $builder->menu[1]['method']);
    }

    public function testNonSearchItemsAreNotAffected()
    {
        // Make the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'About', 'url' => 'about']);
        $builder->add(['header' => 'HEADER']);
        $builder->add(['text' => 'Search', 'search' => false, 'url' => 'search']);

        // Make assertions.

        foreach ($builder->menu as $item) {
            $this->assertArrayNotHasKey('method', $item);
            $this->assertArrayNotHasKey('input_name', $item);
        }
    }
}
