<?php

use JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper;
use JeroenNoten\LaravelAdminLte\Layout\PrintMode;
use JeroenNoten\LaravelAdminLte\Layout\Tokens;

class PrintModeTest extends TestCase
{
    public function testNoAttributesAreMadeWithoutConfig()
    {
        config(['adminlte' => []]);

        $this->assertEquals([], PrintMode::getTokens());
        $this->assertEquals([], PrintMode::makeHtmlAttributes());
    }

    public function testTheTokensAreReadFromAString()
    {
        config(['adminlte.print' => 'plain']);
        $this->assertEquals(['plain'], PrintMode::getTokens());

        config(['adminlte.print' => 'app']);
        $this->assertEquals(['app'], PrintMode::getTokens());

        // The AdminLTE stylesheet matches the attribute per token, so the two
        // tokens may be combined on a single value.

        config(['adminlte.print' => 'plain app']);
        $this->assertEquals(['plain', 'app'], PrintMode::getTokens());

        config(['adminlte.print' => "  app \n plain  "]);
        $this->assertEquals(['plain', 'app'], PrintMode::getTokens());
    }

    public function testTheTokensAreReadFromAnArray()
    {
        config(['adminlte.print' => ['plain']]);
        $this->assertEquals(['plain'], PrintMode::getTokens());

        config(['adminlte.print' => ['app', 'plain']]);
        $this->assertEquals(['plain', 'app'], PrintMode::getTokens());
    }

    public function testUnknownTokensAreDropped()
    {
        config(['adminlte.print' => 'bogus']);
        $this->assertEquals([], PrintMode::getTokens());

        config(['adminlte.print' => ['plain', 'bogus']]);
        $this->assertEquals(['plain'], PrintMode::getTokens());

        config(['adminlte.print' => ['plain', ['nested']]]);
        $this->assertEquals(['plain'], PrintMode::getTokens());
    }

    public function testAnInvalidConfigIsIgnored()
    {
        foreach ([5, true, 3.5, new stdClass()] as $cfg) {
            config(['adminlte.print' => $cfg]);
            $this->assertEquals([], PrintMode::getTokens());
        }
    }

    public function testTheHtmlAttributeIsMade()
    {
        config(['adminlte.print' => 'plain']);

        $this->assertEquals(
            [Tokens::PRINT_ATTRIBUTE.'="plain"'], PrintMode::makeHtmlAttributes()
        );

        config(['adminlte.print' => ['plain', 'app']]);

        $this->assertEquals(
            [Tokens::PRINT_ATTRIBUTE.'="plain app"'], PrintMode::makeHtmlAttributes()
        );
    }

    public function testTheHtmlAttributeReachesTheHtmlTagData()
    {
        config(['adminlte' => []]);

        $this->assertStringNotContainsString(
            Tokens::PRINT_ATTRIBUTE, LayoutHelper::makeHtmlData()
        );

        config(['adminlte.print' => 'plain']);

        $this->assertStringContainsString(
            'data-lte-print="plain"', LayoutHelper::makeHtmlData()
        );
    }
}
