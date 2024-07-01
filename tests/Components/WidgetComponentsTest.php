<?php

use JeroenNoten\LaravelAdminLte\View\Components;

class WidgetComponentsTest extends TestCase
{
    /**
     * Return array with the available blade components.
     */
    protected function getComponents()
    {
        $base = 'adminlte::components.widget';

        return [
            "{$base}.alert" => new Components\Widget\Alert(),
            "{$base}.callout" => new Components\Widget\Callout(),
            "{$base}.card" => new Components\Widget\Card(),
            "{$base}.info-box" => new Components\Widget\InfoBox(),
            "{$base}.profile-col-item" => new Components\Widget\ProfileColItem(),
            "{$base}.profile-row-item" => new Components\Widget\ProfileRowItem(),
            "{$base}.profile-widget" => new Components\Widget\ProfileWidget(),
            "{$base}.progress" => new Components\Widget\Progress(),
            "{$base}.small-box" => new Components\Widget\SmallBox(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | General components tests.
    |--------------------------------------------------------------------------
    */

    public function testAllComponentsRender()
    {
        foreach ($this->getComponents() as $viewName => $component) {
            $view = $component->render();
            $this->assertEquals($view->getName(), $viewName);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Alert component tests.
    |--------------------------------------------------------------------------
    */

    public function testAlertComponentWithoutTheme()
    {
        $component = new Components\Widget\Alert();

        $aClass = $component->makeAlertClass();

        $this->assertStringContainsString('alert', $aClass);
        $this->assertStringContainsString('border', $aClass);
    }

    public function testAlertComponentWithTheme()
    {
        $component = new Components\Widget\Alert('danger', null, null, true);

        $aClass = $component->makeAlertClass();

        $this->assertStringContainsString('alert', $aClass);
        $this->assertStringContainsString('alert-danger', $aClass);
        $this->assertStringContainsString('alert-dismissable', $aClass);
    }

    /*
    |--------------------------------------------------------------------------
    | Callout component tests.
    |--------------------------------------------------------------------------
    */

    public function testCalloutComponent()
    {
        $component = new Components\Widget\Callout('danger');

        $cClass = $component->makeCalloutClass();

        $this->assertStringContainsString('callout', $cClass);
        $this->assertStringContainsString('callout-danger', $cClass);
    }

    /*
    |--------------------------------------------------------------------------
    | Card component tests.
    |--------------------------------------------------------------------------
    */

    public function testCardComponent()
    {
        // Test basic component.

        $component = new Components\Widget\Card('title', null, 'info');

        $cClass = $component->makeCardClass();
        $this->assertStringContainsString('card', $cClass);
        $this->assertStringContainsString('card-info', $cClass);

        $hClass = $component->makeCardHeaderClass();
        $this->assertStringContainsString('card-header', $hClass);

        $cbClass = $component->makeCardBodyClass();
        $this->assertStringContainsString('card-body', $cbClass);

        $fClass = $component->makeCardFooterClass();
        $this->assertStringContainsString('card-footer', $fClass);

        $ctClass = $component->makeCardTitleClass();
        $this->assertStringContainsString('card-title', $ctClass);
    }

    public function testCardComponentWithoutHeader()
    {
        // Test basic component without header.

        $component = new Components\Widget\Card(null, null, 'danger');

        $cClass = $component->makeCardClass();
        $this->assertStringContainsString('card', $cClass);
        $this->assertStringContainsString('card-danger', $cClass);
        $this->assertTrue($component->isCardHeaderEmpty());
        $this->assertFalse($component->isCardHeaderEmpty(true));
    }

    public function testCardComponentCollapsedWithFullThemeMode()
    {
        // Test collapsed mode with full theme and extra body, footer and
        // header classed:
        // $title, $icon, $theme, $themeMode, $headerClass, $bodyClass,
        // $footerClass, $disabled, $collapsible, $removable, $maximizable.

        $component = new Components\Widget\Card(
            'title', null, 'success', 'full', 'header-class', 'body-class',
            'footer-class', null, 'collapsed'
        );

        $cClass = $component->makeCardClass();
        $this->assertStringContainsString('bg-gradient-success', $cClass);
        $this->assertStringContainsString('collapsed-card', $cClass);

        $hClass = $component->makeCardHeaderClass();
        $this->assertStringContainsString('card-header', $hClass);
        $this->assertStringContainsString('header-class', $hClass);

        $cbClass = $component->makeCardBodyClass();
        $this->assertStringContainsString('card-body', $cbClass);
        $this->assertStringContainsString('body-class', $cbClass);

        $fClass = $component->makeCardFooterClass();
        $this->assertStringContainsString('card-footer', $fClass);
        $this->assertStringContainsString('footer-class', $fClass);
    }

    public function testCardComponentWithOutlineThemeMode()
    {
        // Test outline theme:
        // $title, $icon, $theme, $themeMode, $headerClass, $bodyClass,
        // $footerClass, $disabled, $collapsible, $removable, $maximizable.

        $component = new Components\Widget\Card(
            'title', null, 'teal', 'outline'
        );

        $cClass = $component->makeCardClass();
        $this->assertStringContainsString('card-teal', $cClass);
        $this->assertStringContainsString('card-outline', $cClass);

        $ctClass = $component->makeCardTitleClass();
        $this->assertStringContainsString('text-teal', $ctClass);
    }

    /*
    |--------------------------------------------------------------------------
    | Info box component tests.
    |--------------------------------------------------------------------------
    */

    public function testInfoBoxComponent()
    {
        $component = new Components\Widget\InfoBox(
            'title', 'text', 'icon', null, null, null, 'danger', 'primary'
        );

        $bClass = $component->makeBoxClass();
        $this->assertStringContainsString('info-box', $bClass);
        $this->assertStringContainsString('bg-danger', $bClass);

        $iClass = $component->makeIconClass();
        $this->assertStringContainsString('info-box-icon', $iClass);
        $this->assertStringContainsString('bg-primary', $iClass);
    }

    public function testInfoBoxComponentProgressAttribute()
    {
        // Test that the progress attribute always stays in the range [0, 100].

        $component = new Components\Widget\InfoBox();
        $this->assertNull($component->progress);

        $component = new Components\Widget\infoBox(
            null, null, null, null, null, null, null, null, 67
        );
        $this->assertEquals($component->progress, 67);

        $component = new Components\Widget\infoBox(
            null, null, null, null, null, null, null, null, 100
        );
        $this->assertEquals($component->progress, 100);

        $component = new Components\Widget\infoBox(
            null, null, null, null, null, null, null, null, 0
        );
        $this->assertEquals($component->progress, 0);

        $component = new Components\Widget\infoBox(
            null, null, null, null, null, null, null, null, -21.14
        );
        $this->assertEquals($component->progress, 0);

        $component = new Components\Widget\infoBox(
            null, null, null, null, null, null, null, null, 527.67
        );
        $this->assertEquals($component->progress, 100);
    }

    /*
    |--------------------------------------------------------------------------
    | Profile col item component tests.
    |--------------------------------------------------------------------------
    */

    public function testProfileColItemComponent()
    {
        // Test with common badge.

        $component = new Components\Widget\ProfileColItem(
            'title', 'text', null, null, 'b-theme'
        );

        $twClass = $component->makeTextWrapperClass();
        $this->assertStringContainsString('badge', $twClass);
        $this->assertStringContainsString('bg-b-theme', $twClass);

        // Test with common pill badge.

        $component = new Components\Widget\ProfileColItem(
            'title', 'text', null, null, 'pill-b-theme'
        );

        $twClass = $component->makeTextWrapperClass();
        $this->assertStringContainsString('badge-pill', $twClass);
        $this->assertStringContainsString('bg-b-theme', $twClass);
    }

    /*
    |--------------------------------------------------------------------------
    | Profile row item component tests.
    |--------------------------------------------------------------------------
    */

    public function testProfileRowItemComponent()
    {
        // Test with common badge.

        $component = new Components\Widget\ProfileRowItem(
            'title', 'text', null, null, 'b-theme'
        );

        $twClass = $component->makeTextWrapperClass();
        $this->assertStringContainsString('badge', $twClass);
        $this->assertStringContainsString('bg-b-theme', $twClass);

        // Test with common pill badge.

        $component = new Components\Widget\ProfileRowItem(
            'title', 'text', null, null, 'pill-b-theme'
        );

        $twClass = $component->makeTextWrapperClass();
        $this->assertStringContainsString('badge-pill', $twClass);
        $this->assertStringContainsString('bg-b-theme', $twClass);
    }

    /*
    |--------------------------------------------------------------------------
    | Profile widget component tests.
    |--------------------------------------------------------------------------
    */

    public function testProfileWidgetComponentWithoutCover()
    {
        // Test without cover.

        $component = new Components\Widget\ProfileWidget(
            'name', 'description', null, 'danger', null, 'h-class', 'f-class',
            'layout-foo'
        );

        $this->assertEquals('modern', $component->layoutType);

        $cClass = $component->makeCardClass();
        $this->assertStringContainsString('card', $cClass);
        $this->assertStringContainsString('card-widget', $cClass);
        $this->assertStringContainsString('widget-user', $cClass);

        $hClass = $component->makeHeaderClass();
        $this->assertStringContainsString('widget-user-header', $hClass);
        $this->assertStringContainsString('bg-gradient-danger', $hClass);
        $this->assertStringContainsString('h-class', $hClass);

        $fClass = $component->makeFooterClass();
        $this->assertStringContainsString('card-footer', $fClass);
        $this->assertStringContainsString('f-class', $fClass);

        $hStyle = $component->makeHeaderStyle();
        $this->assertTrue(empty($hStyle));
    }

    public function testProfileWidgetComponentWithCoverAndClassicLayout()
    {
        // Test with cover and classic layout.

        $component = new Components\Widget\ProfileWidget(
            'name', 'description', null, 'danger', 'img.png', null, null,
            'classic'
        );

        $cClass = $component->makeCardClass();
        $this->assertStringContainsString('widget-user-2', $cClass);

        $hClass = $component->makeHeaderClass();
        $this->assertStringNotContainsString('bg-gradient-danger', $hClass);

        $hStyle = $component->makeHeaderStyle();
        $this->assertStringContainsString("background: url('img.png')", $hStyle);
    }

    /*
    |--------------------------------------------------------------------------
    | Progress component tests.
    |--------------------------------------------------------------------------
    */

    public function testProgressComponent()
    {
        // Test basic component.

        $component = new Components\Widget\Progress();

        $pClass = $component->makeProgressClass();
        $this->assertStringContainsString('progress', $pClass);

        $pbClass = $component->makeProgressBarClass();
        $this->assertStringContainsString('progress-bar', $pbClass);
        $this->assertStringContainsString('bg-info', $pbClass);

        $pbStyle = $component->makeProgressBarStyle();
        $this->assertStringContainsString('width:0%', $pbStyle);
    }

    public function testProgressComponentWithAdvancedOptions()
    {
        // Test with all constructor arguments:
        // $value, $theme, $size, $striped, $vertical, $animated, $withLabel.

        $component = new Components\Widget\Progress(
            75, 'danger', 'sm', true, true, true, true
        );

        $pClass = $component->makeProgressClass();
        $this->assertStringContainsString('progress', $pClass);
        $this->assertStringContainsString('progress-sm', $pClass);
        $this->assertStringContainsString('vertical', $pClass);

        $pbClass = $component->makeProgressBarClass();
        $this->assertStringContainsString('progress-bar', $pbClass);
        $this->assertStringContainsString('bg-danger', $pbClass);
        $this->assertStringContainsString('progress-bar-striped', $pbClass);
        $this->assertStringContainsString('progress-bar-animated', $pbClass);

        $pbStyle = $component->makeProgressBarStyle();
        $this->assertStringContainsString('height:75%', $pbStyle);
    }

    public function testProgressComponentValueAttribute()
    {
        // test that the value attribute always stays in the range [0, 100].

        $component = new Components\Widget\Progress(67);
        $this->assertEquals($component->value, 67);

        $component = new Components\Widget\Progress(100);
        $this->assertEquals($component->value, 100);

        $component = new Components\Widget\Progress(0);
        $this->assertEquals($component->value, 0);

        $component = new Components\Widget\Progress(-21.14);
        $this->assertEquals($component->value, 0);

        $component = new Components\Widget\Progress(527.67);
        $this->assertEquals($component->value, 100);
    }

    /*
    |--------------------------------------------------------------------------
    | Small box component tests.
    |--------------------------------------------------------------------------
    */

    public function testSmallBoxComponent()
    {
        $component = new Components\Widget\SmallBox(
            'title', 'text', null, 'danger'
        );

        $bClass = $component->makeBoxClass();
        $this->assertStringContainsString('small-box', $bClass);
        $this->assertStringContainsString('bg-danger', $bClass);

        $oClass = $component->makeOverlayClass();
        $this->assertStringContainsString('overlay', $oClass);
        $this->assertStringContainsString('d-none', $oClass);
    }
}
