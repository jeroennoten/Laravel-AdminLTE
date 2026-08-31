<?php

class DataFilterTest extends TestCase
{
    public function testDataAttributesAreCompiled()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add([
            'text' => 'About',
            'data' => [
                'param1' => 'value1',
                'param2' => 'value2',
            ],
        ]);

        // Make assertions.

        $this->assertEquals(
            'data-param1="value1" data-param2="value2"',
            $builder->menu[0]['data-compiled']
        );
    }

    public function testItemsWithoutDataAttributesAreNotAffected()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'About', 'url' => 'about']);
        $builder->add(['header' => 'HEADER']);
        $builder->add(['text' => 'Invalid', 'data' => 'not-an-array']);

        // Make assertions.

        foreach ($builder->menu as $item) {
            $this->assertArrayNotHasKey('data-compiled', $item);
        }
    }

    public function testDataAttributesAreCompiledOnSubmenuItems()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();

        $builder->add([
            'text' => 'Pages',
            'data' => ['level' => '0'],
            'submenu' => [
                [
                    'text' => 'Page 1',
                    'url' => 'pages/1',
                    'data' => ['level' => '1'],
                ],
            ],
        ]);

        // Make assertions.

        $this->assertEquals('data-level="0"', $builder->menu[0]['data-compiled']);

        $this->assertEquals(
            'data-level="1"',
            $builder->menu[0]['submenu'][0]['data-compiled']
        );
    }

    public function testAnEmptyDataArrayIsCompiledToAnEmptyString()
    {
        // Build the menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'About', 'url' => 'about', 'data' => []]);

        // Make assertions.

        $this->assertEquals('', $builder->menu[0]['data-compiled']);
    }
}
