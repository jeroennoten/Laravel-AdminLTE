<?php

class DataFilterTest extends TestCase
{
    public function testDataAttributesAreCompiled()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add([
            'text' => 'About',
            'data' => [
                'param1' => 'value1',
                'param2' => 'value2',
            ],
        ]);

        $this->assertEquals(
            'data-param1="value1" data-param2="value2"',
            $builder->menu[0]['data-compiled']
        );
    }
}
