<?php

require_once __DIR__.'/ComponentTestHelpers.php';

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Lang;
use JeroenNoten\LaravelAdminLte\View\Components;

class ContentHeaderComponentTest extends TestCase
{
    use ComponentTestHelpers;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Blade::component(
            Components\Layout\ContentHeader::class,
            'adminlte-content-header'
        );
    }

    /**
     * Return a set of breadcrumb items covering the linked and the active
     * entries.
     *
     * @return string
     */
    protected function getBreadcrumbsExpression()
    {
        return ":breadcrumbs=\"[['label' => 'Home', 'url' => '/home'], ['label' => 'Dashboard']]\"";
    }

    /*
    |--------------------------------------------------------------------------
    | General component tests.
    |--------------------------------------------------------------------------
    */

    public function testComponentRenders()
    {
        $component = new Components\Layout\ContentHeader();
        $view = $component->render();

        $this->assertEquals(
            'adminlte::components.layout.content-header',
            $view->getName()
        );
    }

    public function testComponentRendersWithEveryAttribute()
    {
        $bc = $this->getBreadcrumbsExpression();

        $html = $this->renderComponent(
            "<x-adminlte-content-header title=\"Dashboard\" {$bc}
                title-class=\"mb-0 fs-1\"/>"
        );

        $this->assertNotEmpty(trim($html));
        $this->assertV4Markup($html);
        $this->assertFreeOfJquery($html);
    }

    public function testComponentRendersWithoutAnyAttribute()
    {
        $html = $this->renderComponent('<x-adminlte-content-header/>');

        $this->assertNotEmpty(trim($html));
        $this->assertV4Markup($html);

        // Only the (empty) title column is rendered.

        $this->assertStringContainsString('class="row"', $html);
        $this->assertStringContainsString('<div class="col-sm-6">', $html);
        $this->assertStringNotContainsString('<h1', $html);
        $this->assertStringNotContainsString('breadcrumb', $html);
        $this->assertEquals(1, substr_count($html, 'col-sm-6'));
    }

    public function testComponentPushesNoAssets()
    {
        $this->renderComponent('<x-adminlte-content-header title="T"/>');

        $this->assertEmpty(trim($this->renderPushedAssets()));
    }

    /*
    |--------------------------------------------------------------------------
    | Title tests.
    |--------------------------------------------------------------------------
    */

    public function testTitleClass()
    {
        // The AdminLTE v4 reference layouts use the 'mb-0 fs-3' classes.

        $component = new Components\Layout\ContentHeader('Dashboard');
        $this->assertEquals('mb-0 fs-3', $component->makeTitleClass());

        // The default classes are fully replaced by the provided ones.

        $component = new Components\Layout\ContentHeader('Dashboard', [], 'display-6');
        $this->assertEquals('display-6', $component->makeTitleClass());
    }

    public function testRendersTheTitle()
    {
        $html = $this->renderComponent(
            '<x-adminlte-content-header title="Dashboard"/>'
        );

        $this->assertStringContainsString(
            '<h1 class="mb-0 fs-3">Dashboard</h1>',
            $html
        );
    }

    public function testRendersTheCustomTitleClass()
    {
        $html = $this->renderComponent(
            '<x-adminlte-content-header title="Dashboard" title-class="h2 mb-3"/>'
        );

        $this->assertStringContainsString(
            '<h1 class="h2 mb-3">Dashboard</h1>',
            $html
        );

        $this->assertStringNotContainsString('fs-3', $html);
    }

    public function testTitleAppliesTheHtmlEntityDecoder()
    {
        $component = new Components\Layout\ContentHeader('R&amp;D');

        $this->assertEquals('R&D', $component->title);
    }

    public function testTitleIsEscapedOnTheMarkup()
    {
        $html = $this->renderComponent(
            '<x-adminlte-content-header title="&lt;script&gt;x&lt;/script&gt;"/>'
        );

        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Breadcrumb tests.
    |--------------------------------------------------------------------------
    */

    public function testBreadcrumbsAreNormalized()
    {
        $component = new Components\Layout\ContentHeader('T', [
            ['label' => 'Home', 'url' => '/home'],
            ['label' => 'Dashboard'],
            'Plain',
        ]);

        $this->assertCount(3, $component->breadcrumbs);

        $this->assertEquals(
            ['label' => 'Home', 'url' => '/home', 'active' => false],
            $component->breadcrumbs[0]
        );

        $this->assertEquals(
            ['label' => 'Dashboard', 'url' => null, 'active' => true],
            $component->breadcrumbs[1]
        );

        $this->assertEquals(
            ['label' => 'Plain', 'url' => null, 'active' => true],
            $component->breadcrumbs[2]
        );
    }

    public function testBreadcrumbsAcceptAnExplicitActiveFlag()
    {
        $component = new Components\Layout\ContentHeader('T', [
            ['label' => 'Home', 'url' => '/home', 'active' => true],
            ['label' => 'Docs', 'active' => false],
        ]);

        $this->assertTrue($component->breadcrumbs[0]['active']);
        $this->assertFalse($component->breadcrumbs[1]['active']);
    }

    public function testBreadcrumbsDiscardTheInvalidEntries()
    {
        $component = new Components\Layout\ContentHeader('T', [
            ['url' => '/home'],
            ['label' => ''],
            ['label' => '   '],
            ['label' => 'Valid'],
        ]);

        $this->assertCount(1, $component->breadcrumbs);
        $this->assertEquals('Valid', $component->breadcrumbs[0]['label']);
    }

    public function testBreadcrumbsAppliesTheHtmlEntityDecoder()
    {
        $component = new Components\Layout\ContentHeader('T', [
            ['label' => 'R&amp;D'],
        ]);

        $this->assertEquals('R&D', $component->breadcrumbs[0]['label']);
    }

    public function testBreadcrumbsWithAnInvalidValueAreIgnored()
    {
        foreach ([null, 'invalid', 66] as $value) {
            $component = new Components\Layout\ContentHeader('T', $value);

            $this->assertEquals([], $component->breadcrumbs);
            $this->assertFalse($component->hasBreadcrumbs());
        }
    }

    public function testBreadcrumbItemClass()
    {
        $component = new Components\Layout\ContentHeader();

        $this->assertEquals(
            'breadcrumb-item',
            $component->makeBreadcrumbItemClass(['active' => false])
        );

        $this->assertEquals(
            'breadcrumb-item active',
            $component->makeBreadcrumbItemClass(['active' => true])
        );
    }

    public function testRendersTheBreadcrumbs()
    {
        $bc = $this->getBreadcrumbsExpression();

        $html = $this->renderComponent(
            "<x-adminlte-content-header title=\"Dashboard\" {$bc}/>"
        );

        // The AdminLTE v4 reference markup. Note the nav landmark label is
        // translatable, its default value matches the reference one.

        $this->assertStringContainsString('<nav aria-label="breadcrumb">', $html);
        $this->assertStringContainsString('<ol class="breadcrumb float-sm-end">', $html);
        $this->assertEquals(2, substr_count($html, 'col-sm-6'));

        // The linked entry is not the active one.

        $this->assertMatchesRegularExpression(
            '/<li class="breadcrumb-item"\s*>\s*<a href="\/home">Home<\/a>/',
            $html
        );

        // The entry without url is the active one.

        $this->assertMatchesRegularExpression(
            '/<li class="breadcrumb-item active"\s+aria-current="page"\s*>\s*Dashboard/',
            $html
        );

        // Only the active entry carries the aria-current attribute.

        $this->assertEquals(1, substr_count($html, 'aria-current="page"'));

        $this->assertV4Markup($html);
    }

    public function testRendersNoBreadcrumbColumnWhenThereAreNoBreadcrumbs()
    {
        $html = $this->renderComponent(
            '<x-adminlte-content-header title="Dashboard" :breadcrumbs="[]"/>'
        );

        $this->assertStringContainsString('<h1 class="mb-0 fs-3">Dashboard</h1>', $html);
        $this->assertStringNotContainsString('<nav', $html);
        $this->assertStringNotContainsString('breadcrumb', $html);
        $this->assertEquals(1, substr_count($html, 'col-sm-6'));
    }

    public function testBreadcrumbLabelIsEscapedOnTheMarkup()
    {
        $html = $this->renderComponent(
            '<x-adminlte-content-header :breadcrumbs="[
                [\'label\' => \'<b>x</b>\'],
                [\'label\' => \'<i>y</i>\', \'url\' => \'/y\'],
            ]"/>'
        );

        $this->assertStringNotContainsString('<b>x</b>', $html);
        $this->assertStringNotContainsString('<i>y</i>', $html);
        $this->assertStringContainsString('&lt;b&gt;x&lt;/b&gt;', $html);
        $this->assertStringContainsString('&lt;i&gt;y&lt;/i&gt;', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Slots tests.
    |--------------------------------------------------------------------------
    */

    public function testDefaultSlotReplacesTheTitle()
    {
        $html = $this->renderComponent(
            '<x-adminlte-content-header title="Ignored">
                <h1 class="custom">Custom title</h1>
            </x-adminlte-content-header>'
        );

        $this->assertStringContainsString('<h1 class="custom">Custom title</h1>', $html);
        $this->assertStringNotContainsString('Ignored', $html);
        $this->assertStringNotContainsString('fs-3', $html);
    }

    public function testBreadcrumbSlotReplacesTheBreadcrumbs()
    {
        $bc = $this->getBreadcrumbsExpression();

        $html = $this->renderComponent(
            "<x-adminlte-content-header title=\"Dashboard\" {$bc}>
                <x-slot name=\"breadcrumbSlot\"><span>Custom trail</span></x-slot>
            </x-adminlte-content-header>"
        );

        $this->assertStringContainsString('<span>Custom trail</span>', $html);
        $this->assertStringNotContainsString('<nav', $html);
        $this->assertStringNotContainsString('breadcrumb-item', $html);

        // The default slot is empty, so the title is still rendered.

        $this->assertStringContainsString('<h1 class="mb-0 fs-3">Dashboard</h1>', $html);
        $this->assertEquals(2, substr_count($html, 'col-sm-6'));
    }

    public function testBreadcrumbSlotRendersTheColumnWithoutAnyBreadcrumb()
    {
        $html = $this->renderComponent(
            '<x-adminlte-content-header title="Dashboard">
                <x-slot name="breadcrumbSlot">Trail</x-slot>
            </x-adminlte-content-header>'
        );

        $this->assertStringContainsString('Trail', $html);
        $this->assertEquals(2, substr_count($html, 'col-sm-6'));
    }

    /*
    |--------------------------------------------------------------------------
    | Attributes tests.
    |--------------------------------------------------------------------------
    */

    public function testExtraAttributesAreForwardedToTheRootElement()
    {
        $html = $this->renderComponent(
            '<x-adminlte-content-header id="ch" title="T" class="mb-4"
                data-cy="header"/>'
        );

        $this->assertStringContainsString('id="ch"', $html);
        $this->assertStringContainsString('data-cy="header"', $html);

        // The extra classes are merged with the 'row' class.

        $this->assertMatchesRegularExpression('/class="[^"]*\brow\b/', $html);
        $this->assertMatchesRegularExpression('/class="[^"]*\bmb-4\b/', $html);
    }

    public function testBreadcrumbNavLabelIsTranslatable()
    {
        Lang::addLines(['adminlte.breadcrumb' => 'ruta'], 'xx', 'adminlte');
        $this->app->setLocale('xx');

        $html = $this->renderComponent(
            '<x-adminlte-content-header :breadcrumbs="[\'Home\']"/>'
        );

        $this->assertStringContainsString('<nav aria-label="ruta">', $html);
    }
}
