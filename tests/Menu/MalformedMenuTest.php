<?php

use JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter;

class MalformedMenuTest extends TestCase
{
    public function testMenuDeclaredAsAnAssociativeArrayIsCompiled()
    {
        // A menu configuration written with string keys used to abort the
        // whole compilation, since the keys reached a variadic call.

        config([
            'adminlte.menu' => [
                'home' => ['text' => 'Home', 'url' => '/'],
                'about' => ['text' => 'About', 'url' => '/about'],
            ],
        ]);

        $adminlte = $this->makeAdminLte([HrefFilter::class]);
        $menu = $adminlte->menu();

        $this->assertCount(2, $menu);
        $this->assertEquals('Home', $menu[0]['text']);
        $this->assertEquals('About', $menu[1]['text']);
    }

    public function testItemsAddedWithStringKeysAreCompiled()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(...['home' => ['text' => 'Home', 'url' => '/']]);
        $builder->add(['key' => 'about', 'text' => 'About', 'url' => '/about']);
        $builder->addIn('about', ...['sub' => ['text' => 'Sub', 'url' => '/sub']]);

        $menu = $builder->menu;

        $this->assertCount(2, $menu);
        $this->assertEquals('Home', $menu[0]['text']);
        $this->assertEquals('Sub', $menu[1]['submenu'][0]['text']);
    }

    public function testANonArrayMenuConfigurationIsIgnored()
    {
        config(['adminlte.menu' => 'not-a-menu']);

        $this->assertEmpty($this->makeAdminLte()->menu());
    }

    public function testANonArrayFiltersConfigurationIsIgnored()
    {
        config(['adminlte.filters' => 'not-a-filter-set']);
        config(['adminlte.menu' => [['text' => 'Home', 'url' => '/']]]);

        $menu = app(\JeroenNoten\LaravelAdminLte\AdminLte::class)->menu();

        $this->assertCount(1, $menu);
    }

    public function testAnActivePropertyGivenAsAStringDoesNotBreakTheMenu()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add([
            'text' => 'About',
            'url' => '/about',
            'active' => 'admin/*',
        ]);

        $this->assertFalse($builder->menu[0]['active']);
    }

    public function testAMalformedRegexPatternDoesNotBreakTheMenu()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add([
            'text' => 'About',
            'url' => '/about',
            'active' => ['regex:@/unterminated'],
        ]);

        $this->assertFalse($builder->menu[0]['active']);
    }

    public function testAnUnknownRouteNameDoesNotBreakTheMenu()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Missing', 'route' => 'this.route.does.not.exist']);
        $builder->add(['text' => 'Partial', 'route' => ['this.one.neither', ['id' => 1]]]);
        $builder->add(['text' => 'Empty', 'route' => ['']]);

        foreach ($builder->menu as $item) {
            $this->assertEquals('#', $item['href']);
        }
    }

    public function testARouteWithoutItsRequiredParametersDoesNotBreakTheMenu()
    {
        \Illuminate\Support\Facades\Route::get('items/{id}', function () {
            return '';
        })->name('items.show');

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'Item', 'route' => 'items.show']);

        $this->assertEquals('#', $builder->menu[0]['href']);
    }

    public function testANonStringUrlDoesNotBreakTheMenu()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add(['text' => 'Weird', 'url' => ['not', 'a', 'url']]);

        $this->assertEquals('#', $builder->menu[0]['href']);
    }

    public function testANonScalarDataAttributeIsDropped()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add([
            'text' => 'About',
            'url' => '/about',
            'data' => ['ok' => 'yes', 'bad' => ['nested']],
        ]);

        $this->assertEquals('data-ok="yes"', $builder->menu[0]['data-compiled']);
    }

    public function testDataAttributeValuesAreEscaped()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add([
            'text' => 'About',
            'url' => '/about',
            'data' => ['title' => 'a "quoted" value'],
        ]);

        $this->assertEquals(
            'data-title="a &quot;quoted&quot; value"',
            $builder->menu[0]['data-compiled']
        );
    }

    public function testANonStringSearchMethodFallsBackToTheDefault()
    {
        $builder = $this->makeMenuBuilder();

        $builder->add([
            'text' => 'Search',
            'search' => true,
            'method' => ['post'],
        ]);

        $this->assertEquals('get', $builder->menu[0]['method']);
    }
}
