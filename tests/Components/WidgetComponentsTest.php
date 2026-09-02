<?php

require_once __DIR__.'/ComponentTestHelpers.php';

use JeroenNoten\LaravelAdminLte\View\Components;

class WidgetComponentsTest extends TestCase
{
    use ComponentTestHelpers;

    /**
     * Return array with a template exercising every attribute of each one of
     * the available widget components.
     *
     * @return array
     */
    protected function getFullyAttributedTemplates()
    {
        return [
            'alert' => '<x-adminlte-alert theme="info" icon="bi bi-bell"
                title="Title" dismissable>Content</x-adminlte-alert>',
            'callout' => '<x-adminlte-callout theme="info" icon="bi bi-bell"
                title="Title" title-class="mb-3">Content</x-adminlte-callout>',
            'card' => '<x-adminlte-card title="Title" icon="bi bi-bell" theme="info"
                theme-mode="outline" header-class="h-cls" body-class="b-cls"
                footer-class="f-cls" disabled collapsible removable maximizable>
                Content<x-slot name="footerSlot">Footer</x-slot></x-adminlte-card>',
            'info-box' => '<x-adminlte-info-box title="Title" text="Text"
                icon="bi bi-bell" description="Desc" url="/u" url-target="title"
                theme="info" icon-theme="primary" :progress="40"
                progress-theme="danger"/>',
            'profile-col-item' => '<x-adminlte-profile-col-item title="Title"
                text="Text" icon="bi bi-bell" :size="6" badge="pill-info"
                url="/u" url-target="text" text-tooltip="Tip"/>',
            'profile-row-item' => '<x-adminlte-profile-row-item title="Title"
                text="Text" icon="bi bi-bell" :size="6" badge="info"
                url="/u" url-target="title" text-tooltip="Tip"/>',
            'profile-widget' => '<x-adminlte-profile-widget name="Name"
                desc="Desc" img="/i.png" theme="info" cover="/c.png"
                header-class="h-cls" footer-class="f-cls" layout-type="classic"
                icon="bi bi-person"/>',
            'progress' => '<x-adminlte-progress :value="40" theme="info" size="sm"
                striped vertical animated with-label/>',
            'small-box' => '<x-adminlte-small-box title="Title" text="Text"
                icon="bi bi-bell" theme="info" url="/u" url-text="More" loading/>',
        ];
    }

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

    public function testAllComponentsRenderWithEveryAttribute()
    {
        // Every attribute of every widget (including the ones that are only
        // accepted for backward compatibility) must render without breaking
        // and without any of the legacy AdminLTE v3 / Bootstrap 4 markup.

        foreach ($this->getFullyAttributedTemplates() as $name => $template) {
            $html = $this->renderComponent($template);

            $this->assertNotEmpty(trim($html), "The {$name} component is empty.");
            $this->assertV4Markup($html);
            $this->assertFreeOfJquery($html);

            // The widgets are fully jQuery free on AdminLTE v4.

            $this->assertFreeOfJquery($this->renderPushedAssets());
            $this->assertV4Markup($this->renderPushedAssets());
        }
    }

    public function testAllComponentsRenderWithoutAnyAttribute()
    {
        $templates = [
            '<x-adminlte-alert/>', '<x-adminlte-callout/>', '<x-adminlte-card/>',
            '<x-adminlte-info-box/>', '<x-adminlte-profile-col-item/>',
            '<x-adminlte-profile-row-item/>', '<x-adminlte-profile-widget/>',
            '<x-adminlte-progress/>', '<x-adminlte-small-box/>',
        ];

        foreach ($templates as $template) {
            $html = $this->renderComponent($template);

            $this->assertNotEmpty(trim($html));
            $this->assertV4Markup($html);
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
        $this->assertStringContainsString('alert-dismissible', $aClass);
    }

    public function testAlertComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $theme, $icon, $title, $dismissable.

        $html = $this->renderComponent(
            '<x-adminlte-alert theme="danger" icon="bi bi-x-octagon" title="Oops"
                dismissable class="mb-0">The message</x-adminlte-alert>'
        );

        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringContainsString('alert-danger', $html);
        $this->assertStringContainsString('mb-0', $html);
        $this->assertStringContainsString('<h5 class="alert-heading">', $html);
        $this->assertStringContainsString('<i class="bi bi-x-octagon me-2"', $html);
        $this->assertStringContainsString('Oops', $html);
        $this->assertStringContainsString('The message', $html);

        // The Bootstrap 5 dismiss markup requires the 'fade' and 'show'
        // classes together with a 'btn-close' button.

        $this->assertStringContainsString('alert-dismissible fade show', $html);
        $this->assertStringContainsString(
            '<button type="button" class="btn-close" data-bs-dismiss="alert"',
            $html
        );

        $this->assertV4Markup($html);
    }

    public function testAlertComponentUsesTheDefaultThemeIcons()
    {
        // The default icons of the alert themes are Bootstrap Icons.

        $icons = [
            'dark' => 'bi bi-lightning-fill',
            'light' => 'bi bi-lightbulb',
            'primary' => 'bi bi-bell-fill',
            'secondary' => 'bi bi-tag-fill',
            'info' => 'bi bi-info-circle-fill',
            'success' => 'bi bi-check-circle-fill',
            'warning' => 'bi bi-exclamation-triangle-fill',
            'danger' => 'bi bi-x-octagon-fill',
        ];

        foreach ($icons as $theme => $icon) {
            $component = new Components\Widget\Alert($theme);
            $this->assertEquals($icon, $component->icon);
        }

        // A custom icon always takes precedence over the default one.

        $component = new Components\Widget\Alert('danger', 'bi bi-star');
        $this->assertEquals('bi bi-star', $component->icon);

        // A themeless alert has no default icon and gets a plain border.

        $component = new Components\Widget\Alert();
        $this->assertNull($component->icon);
        $this->assertEquals('alert border', $component->makeAlertClass());
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

    public function testCalloutComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $theme, $icon, $title, $titleClass.

        $html = $this->renderComponent(
            '<x-adminlte-callout theme="warning" icon="bi bi-exclamation-triangle"
                title="Careful" title-class="fw-bold mb-3">Body</x-adminlte-callout>'
        );

        $this->assertStringContainsString('class="callout callout-warning"', $html);
        $this->assertStringContainsString('<h5 class="fw-bold mb-3">', $html);
        $this->assertStringContainsString('<i class="bi bi-exclamation-triangle me-2"', $html);
        $this->assertStringContainsString('Careful', $html);
        $this->assertStringContainsString('Body', $html);

        $this->assertV4Markup($html);
    }

    public function testCalloutComponentWithoutTitleAndTheme()
    {
        $html = $this->renderComponent('<x-adminlte-callout>Body</x-adminlte-callout>');

        $this->assertStringContainsString('class="callout"', $html);
        $this->assertStringNotContainsString('<h5', $html);

        // The default title class is only used when a title is available.

        $html = $this->renderComponent(
            '<x-adminlte-callout title="T">Body</x-adminlte-callout>'
        );

        $this->assertStringContainsString('<h5 class="mb-1">', $html);
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
        $this->assertStringContainsString('text-bg-success', $cClass);
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

        // The AdminLTE v4 outline cards keep a plain title.

        $ctClass = $component->makeCardTitleClass();
        $this->assertEquals('card-title', $ctClass);
    }

    public function testCardComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $title, $icon, $theme, $themeMode, $headerClass, $bodyClass,
        // $footerClass, $disabled, $collapsible, $removable, $maximizable.

        $html = $this->renderComponent(
            '<x-adminlte-card title="Title" icon="bi bi-bell" theme="success"
                theme-mode="outline" header-class="h-cls" body-class="b-cls"
                footer-class="f-cls" disabled collapsible removable maximizable>'.
            'The body'.
            '<x-slot name="toolsSlot"><span id="tool">T</span></x-slot>'.
            '<x-slot name="footerSlot">The footer</x-slot>'.
            '</x-adminlte-card>'
        );

        $this->assertStringContainsString('card mb-4 card-success card-outline', $html);
        $this->assertStringContainsString('class="card-header h-cls"', $html);
        $this->assertStringContainsString('class="card-title"', $html);
        $this->assertStringContainsString('<i class="bi bi-bell me-1"', $html);
        $this->assertStringContainsString('class="card-body b-cls"', $html);
        $this->assertStringContainsString('The body', $html);
        $this->assertStringContainsString('class="card-footer f-cls"', $html);
        $this->assertStringContainsString('The footer', $html);
        $this->assertStringContainsString('<span id="tool">T</span>', $html);

        // The disabled card shows an overlay built with Bootstrap utilities.

        $this->assertStringContainsString('card-overlay', $html);
        $this->assertStringContainsString('visually-hidden', $html);

        $this->assertV4Markup($html);
    }

    public function testCardComponentToolsUseTheV4DataHooks()
    {
        // On AdminLTE v4 the card tools are driven by the 'data-lte-toggle'
        // attribute, the legacy 'data-card-widget' attribute is gone.

        $html = $this->renderComponent(
            '<x-adminlte-card title="T" collapsible removable maximizable/>'
        );

        $this->assertStringContainsString('data-lte-toggle="card-maximize"', $html);
        $this->assertStringContainsString('data-lte-toggle="card-collapse"', $html);
        $this->assertStringContainsString('data-lte-toggle="card-remove"', $html);
        $this->assertStringContainsString('data-lte-icon="maximize"', $html);
        $this->assertStringContainsString('data-lte-icon="minimize"', $html);
        $this->assertStringContainsString('data-lte-icon="expand"', $html);
        $this->assertStringContainsString('data-lte-icon="collapse"', $html);
        $this->assertEquals(3, substr_count($html, 'class="btn btn-tool"'));

        // The tool icons are Bootstrap Icons.

        $this->assertStringContainsString('bi bi-fullscreen', $html);
        $this->assertStringContainsString('bi bi-plus-lg', $html);
        $this->assertStringContainsString('bi bi-dash-lg', $html);
        $this->assertStringContainsString('bi bi-x-lg', $html);

        $this->assertV4Markup($html);
    }

    public function testCardComponentKeepsASingleBottomMargin()
    {
        // Regression: the AdminLTE v4 stylesheet gives the cards no bottom
        // margin, so the component adds one, but only when the caller does not
        // provide a margin utility of its own.

        $html = $this->renderComponent('<x-adminlte-card/>');
        $this->assertStringContainsString('class="card mb-4"', $html);

        foreach (['mb-0', 'mb-5', 'my-2', 'mb-auto'] as $margin) {
            $html = $this->renderComponent("<x-adminlte-card class=\"{$margin}\"/>");

            $this->assertStringContainsString($margin, $html);
            $this->assertStringNotContainsString('mb-4', $html);
            $this->assertEquals(1, preg_match_all('/\bm[by]-(auto|[0-5])\b/', $html));
        }

        // A class that is not a margin utility does not disable the default.

        $html = $this->renderComponent('<x-adminlte-card class="shadow-sm"/>');
        $this->assertStringContainsString('mb-4', $html);
        $this->assertStringContainsString('shadow-sm', $html);
    }

    public function testCardComponentRendersOnlyTheAvailableSections()
    {
        // Without title, icon, tools and slots the card is just a container.

        $html = $this->renderComponent('<x-adminlte-card/>');

        $this->assertStringNotContainsString('card-header', $html);
        $this->assertStringNotContainsString('card-body', $html);
        $this->assertStringNotContainsString('card-footer', $html);
        $this->assertStringNotContainsString('card-overlay', $html);

        // The header shows up as soon as one of its items is available.

        $this->assertStringContainsString(
            'card-header',
            $this->renderComponent('<x-adminlte-card icon="bi bi-bell"/>')
        );

        $this->assertStringContainsString(
            'card-header',
            $this->renderComponent('<x-adminlte-card collapsible/>')
        );

        $this->assertStringContainsString(
            'card-header',
            $this->renderComponent(
                '<x-adminlte-card><x-slot name="toolsSlot">T</x-slot></x-adminlte-card>'
            )
        );
    }

    public function testCardComponentWithFullThemeModeRendersTheContrastHelper()
    {
        // On AdminLTE v4 there are no 'bg-gradient-{color}' classes anymore, a
        // fully colored card is made with the Bootstrap 'text-bg-{color}'
        // helper.

        $html = $this->renderComponent(
            '<x-adminlte-card title="T" theme="success" theme-mode="full"/>'
        );

        $this->assertStringContainsString('text-bg-success', $html);
        $this->assertStringNotContainsString('bg-gradient', $html);
        $this->assertStringNotContainsString('card-success', $html);
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

    public function testInfoBoxComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $title, $text, $icon, $description, $url, $urlTarget, $theme,
        // $iconTheme, $progress, $progressTheme.

        $html = $this->renderComponent(
            '<x-adminlte-info-box id="ibox" title="Title" text="1024"
                icon="bi bi-bag" description="Some description" url="/orders"
                url-target="text" theme="info" icon-theme="danger"
                :progress="40" progress-theme="warning"/>'
        );

        $this->assertStringContainsString('class="info-box text-bg-info"', $html);
        $this->assertStringContainsString('class="info-box-icon text-bg-danger shadow-sm"', $html);
        $this->assertStringContainsString('<i class="bi bi-bag"', $html);
        $this->assertStringContainsString('class="info-box-content"', $html);
        $this->assertStringContainsString('class="info-box-text"', $html);
        $this->assertStringContainsString('class="info-box-number"', $html);
        $this->assertStringContainsString('class="progress-description"', $html);
        $this->assertStringContainsString('Some description', $html);

        // The url is attached to the configured target only.

        $this->assertStringContainsString('href="/orders">1024</a>', $html);
        $this->assertEquals(1, substr_count($html, 'info-box-url'));

        // The progress bar is a nested progress component with a predictable
        // id, so the javascript helper can update it.

        $this->assertStringContainsString('id="progress-ibox"', $html);
        $this->assertStringContainsString('bg-warning', $html);
        $this->assertStringContainsString('aria-valuenow="40"', $html);

        $this->assertV4Markup($html);
    }

    public function testInfoBoxComponentUrlTargetsTheTitleByDefault()
    {
        $html = $this->renderComponent(
            '<x-adminlte-info-box title="Title" text="Text" url="/u"/>'
        );

        $this->assertStringContainsString('href="/u">Title</a>', $html);
        $this->assertEquals(1, substr_count($html, 'info-box-url'));

        // The link uses the Bootstrap 5.3 link utilities.

        $this->assertStringContainsString('link-underline-opacity-25', $html);
        $this->assertStringContainsString('text-reset', $html);
    }

    public function testInfoBoxComponentProgressThemeFallbacks()
    {
        // A themeless box paints its progress bar with the primary color.

        $component = new Components\Widget\InfoBox();
        $this->assertEquals('primary', $component->makeProgressTheme());

        // A themed box lets the AdminLTE v4 stylesheet paint the bar with the
        // contrast color of the box.

        $component = new Components\Widget\InfoBox(
            null, null, null, null, null, null, 'info'
        );

        $this->assertEquals('', $component->makeProgressTheme());

        $html = $this->renderComponent(
            '<x-adminlte-info-box title="T" theme="info" :progress="40"/>'
        );

        $this->assertStringContainsString('class="progress-bar fw-bold"', $html);
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
        $this->assertStringContainsString('rounded-pill', $twClass);
        $this->assertStringContainsString('bg-b-theme', $twClass);
    }

    public function testProfileColItemComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $title, $text, $icon, $size, $badge, $url, $urlTarget, $textTooltip.

        $html = $this->renderComponent(
            '<x-adminlte-profile-col-item title="Title" text="Text"
                icon="bi bi-bell" :size="6" badge="pill-info" url="/u"
                url-target="text" text-tooltip="A tooltip"/>'
        );

        $this->assertStringContainsString('class="col-6"', $html);
        $this->assertStringContainsString('class="description-block"', $html);
        $this->assertStringContainsString('<i class="bi bi-bell"', $html);
        $this->assertStringContainsString('class="description-header"', $html);
        $this->assertStringContainsString('class="description-text"', $html);
        $this->assertStringContainsString('badge text-bg-info rounded-pill', $html);
        $this->assertStringContainsString('title="A tooltip"', $html);
        $this->assertStringContainsString('href="/u">Text</a>', $html);

        $this->assertV4Markup($html);
    }

    public function testProfileColItemComponentDefaults()
    {
        $html = $this->renderComponent(
            '<x-adminlte-profile-col-item title="Title" text="Text" url="/u"/>'
        );

        // The default size is 4 and the default url target is the title.

        $this->assertStringContainsString('class="col-4"', $html);
        $this->assertStringContainsString('href="/u">Title</a>', $html);

        // Without a badge, the text wrapper carries no class at all.

        $component = new Components\Widget\ProfileColItem('t', 'x');
        $this->assertEquals('', $component->makeTextWrapperClass());
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
        $this->assertStringContainsString('rounded-pill', $twClass);
        $this->assertStringContainsString('bg-b-theme', $twClass);
    }

    public function testProfileRowItemComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $title, $text, $icon, $size, $badge, $url, $urlTarget, $textTooltip.

        $html = $this->renderComponent(
            '<x-adminlte-profile-row-item title="Title" text="Text"
                icon="bi bi-bell" :size="6" badge="info" url="/u"
                url-target="title" text-tooltip="A tooltip"/>'
        );

        $this->assertStringContainsString('class="p-0 col-6"', $html);
        $this->assertStringContainsString('class="nav-link"', $html);
        $this->assertStringContainsString('<i class="bi bi-bell me-1"', $html);
        $this->assertStringContainsString('title="A tooltip"', $html);
        $this->assertStringContainsString('href="/u">Title</a>', $html);

        // The text is pushed to the end with the Bootstrap 5 logical float.

        $this->assertStringContainsString('float-end badge text-bg-info', $html);

        $this->assertV4Markup($html);
    }

    public function testProfileRowItemComponentDefaults()
    {
        $html = $this->renderComponent(
            '<x-adminlte-profile-row-item title="Title" text="Text"/>'
        );

        // The default size is 12 and the text is floated even without badge.

        $this->assertStringContainsString('class="p-0 col-12"', $html);
        $this->assertStringContainsString('class="float-end"', $html);

        $component = new Components\Widget\ProfileRowItem('t', 'x');
        $this->assertEquals('float-end', $component->makeTextWrapperClass());
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
        $this->assertStringContainsString('widget-user', $cClass);

        $hClass = $component->makeHeaderClass();
        $this->assertStringContainsString('widget-user-header', $hClass);
        $this->assertStringContainsString('text-bg-danger', $hClass);
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
        $this->assertStringNotContainsString('text-bg-danger', $hClass);

        $hStyle = $component->makeHeaderStyle();
        $this->assertStringContainsString("background: url('img.png')", $hStyle);
    }

    public function testProfileWidgetComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $name, $desc, $img, $theme, $cover, $headerClass, $footerClass,
        // $layoutType, $icon.

        $html = $this->renderComponent(
            '<x-adminlte-profile-widget name="John" desc="Developer"
                img="/user.png" theme="info" cover="/cover.png"
                header-class="h-cls" footer-class="f-cls" layout-type="classic"
                icon="bi bi-person-circle">'.
            '<x-adminlte-profile-col-item title="Followers" text="12"/>'.
            '</x-adminlte-profile-widget>'
        );

        $this->assertStringContainsString('class="card mb-4 widget-user-2"', $html);
        $this->assertStringContainsString('widget-user-header h-cls', $html);
        $this->assertStringContainsString('background: url(&#039;/cover.png&#039;)', $html);
        $this->assertStringContainsString('class="widget-user-image"', $html);
        $this->assertStringContainsString('src="/user.png"', $html);
        $this->assertStringContainsString('class="widget-user-username">John', $html);
        $this->assertStringContainsString('class="widget-user-desc">Developer', $html);
        $this->assertStringContainsString('class="card-footer f-cls"', $html);
        $this->assertStringContainsString('Followers', $html);

        // The cover overlays the theme.

        $this->assertStringNotContainsString('text-bg-info', $html);

        $this->assertV4Markup($html);
    }

    public function testProfileWidgetComponentFallsBackToTheDefaultIcon()
    {
        // Without an image, a Bootstrap Icon placeholder is rendered.

        $html = $this->renderComponent('<x-adminlte-profile-widget name="John"/>');

        $this->assertStringContainsString('bi bi-person-fill', $html);
        $this->assertStringNotContainsString('<img', $html);

        $html = $this->renderComponent(
            '<x-adminlte-profile-widget name="John" icon="bi bi-robot"/>'
        );

        $this->assertStringContainsString('bi bi-robot', $html);
    }

    public function testProfileWidgetComponentFallsBackToTheModernLayout()
    {
        // An unknown layout type is replaced by the default one.

        foreach (['modern', 'unknown-layout', ''] as $layout) {
            $component = new Components\Widget\ProfileWidget(
                null, null, null, null, null, null, null, $layout
            );

            $this->assertEquals('modern', $component->layoutType);
        }

        $html = $this->renderComponent(
            '<x-adminlte-profile-widget name="John" layout-type="foo"/>'
        );

        $this->assertStringContainsString('widget-user', $html);
        $this->assertStringNotContainsString('widget-user-2', $html);

        // The footer is always rendered, it reserves the space of the user
        // image on the AdminLTE v4 widget.

        $this->assertStringContainsString('class="card-footer"', $html);
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

    public function testProgressComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $value, $theme, $size, $striped, $vertical, $animated, $withLabel.

        $html = $this->renderComponent(
            '<x-adminlte-progress :value="75" theme="danger" size="xs" striped
                vertical animated with-label/>'
        );

        $this->assertStringContainsString('progress mb-2 progress-xs vertical', $html);
        $this->assertStringContainsString('progress-bar fw-bold text-bg-danger', $html);
        $this->assertStringContainsString('progress-bar-striped', $html);
        $this->assertStringContainsString('progress-bar-animated', $html);
        $this->assertStringContainsString('style="height:75%"', $html);
        $this->assertStringContainsString('75%', $html);

        // On the Bootstrap 5.3 markup the aria attributes live on the wrapper.

        $this->assertStringContainsString('role="progressbar"', $html);
        $this->assertStringContainsString('aria-valuenow="75"', $html);
        $this->assertStringContainsString('aria-valuemin="0"', $html);
        $this->assertStringContainsString('aria-valuemax="100"', $html);
        $this->assertStringContainsString('aria-label="Progress"', $html);

        $this->assertV4Markup($html);
    }

    public function testProgressComponentKeepsASingleBottomMargin()
    {
        // Regression: the AdminLTE v4 stylesheet gives the progress bars no
        // margin, so the component adds one, but only when the caller does not
        // provide a margin utility of its own.

        $html = $this->renderComponent('<x-adminlte-progress/>');
        $this->assertStringContainsString('class="progress mb-2"', $html);

        foreach (['mb-0', 'my-3', 'mb-auto'] as $margin) {
            $html = $this->renderComponent("<x-adminlte-progress class=\"{$margin}\"/>");

            $this->assertStringContainsString($margin, $html);
            $this->assertStringNotContainsString('mb-2', $html);
            $this->assertEquals(1, preg_match_all('/\bm[by]-(auto|[0-5])\b/', $html));
        }
    }

    public function testProgressComponentSizes()
    {
        foreach (['sm', 'xs', 'xxs'] as $size) {
            $html = $this->renderComponent("<x-adminlte-progress size=\"{$size}\"/>");

            $this->assertStringContainsString("progress-{$size}", $html);
        }

        // An unknown size is ignored.

        $html = $this->renderComponent('<x-adminlte-progress size="huge"/>');

        $this->assertStringNotContainsString('progress-huge', $html);
    }

    public function testProgressComponentWithoutThemeInheritsTheContainerColor()
    {
        $component = new Components\Widget\Progress(50, '');

        $this->assertEquals(
            'progress-bar fw-bold',
            $component->makeProgressBarClass()
        );
    }

    public function testProgressComponentRegistersTheJavascriptHelper()
    {
        $this->renderComponent('<x-adminlte-progress :value="10"/>');

        $js = $this->renderPushedAssets();

        $this->assertStringContainsString('class _AdminLTE_Progress', $js);
        $this->assertFreeOfJquery($js);
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

    public function testSmallBoxComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $title, $text, $icon, $theme, $url, $urlText, $loading.

        $html = $this->renderComponent(
            '<x-adminlte-small-box title="150" text="New orders" icon="bi bi-bag"
                theme="success" url="/orders" url-text="More info" loading/>'
        );

        $this->assertStringContainsString('class="small-box text-bg-success"', $html);
        $this->assertStringContainsString('<div class="inner">', $html);
        $this->assertStringContainsString('<h3>150</h3>', $html);
        $this->assertStringContainsString('<p>New orders</p>', $html);
        $this->assertStringContainsString('href="/orders"', $html);
        $this->assertStringContainsString('small-box-footer', $html);
        $this->assertStringContainsString('More info', $html);
        $this->assertStringContainsString('bi bi-arrow-right-circle', $html);

        // The icon keeps a tight line height, so an icon font does not
        // overflow the reserved box.

        $this->assertStringContainsString('small-box-icon lh-1 bi bi-bag', $html);

        // The loading overlay is visible when the attribute is provided.

        $this->assertStringContainsString('small-box-overlay', $html);
        $this->assertStringContainsString('spinner-border', $html);
        $this->assertStringNotContainsString('d-none', $html);

        $this->assertV4Markup($html);
    }

    public function testSmallBoxComponentHidesTheOverlayByDefault()
    {
        $html = $this->renderComponent('<x-adminlte-small-box title="1"/>');

        $this->assertStringContainsString('small-box-overlay', $html);
        $this->assertStringContainsString('d-none', $html);

        // The AdminLTE v3 '.overlay' class no longer exists on v4, the overlay
        // is built with Bootstrap utilities.

        $component = new Components\Widget\SmallBox();
        $oClass = $component->makeOverlayClass();

        $this->assertStringContainsString('position-absolute', $oClass);
        $this->assertStringContainsString('bg-body', $oClass);
        $this->assertStringContainsString('bg-opacity-75', $oClass);
    }

    public function testSmallBoxComponentRegistersTheJavascriptHelper()
    {
        $this->renderComponent('<x-adminlte-small-box title="1"/>');

        $js = $this->renderPushedAssets();

        $this->assertStringContainsString('class _AdminLTE_SmallBox', $js);
        $this->assertStringContainsString('toggleLoading', $js);
        $this->assertFreeOfJquery($js);
    }

    public function testProfileWidgetCarriesTheSameSpacingAsACard()
    {
        $widget = new Components\Widget\ProfileWidget('Jane', 'Dev');
        $card = new Components\Widget\Card();

        $this->assertStringContainsString('mb-4', $widget->makeCardClass());
        $this->assertStringContainsString('mb-4', $card->makeCardClass());
    }

    public function testSmallBoxFooterLinkFollowsTheBackgroundContrast()
    {
        // The underline suppression needs a 'link-*' class, so the link color
        // has to follow the contrast of the box background.

        $box = new Components\Widget\SmallBox(null, null, null, 'warning');
        $this->assertStringContainsString('link-dark', $box->makeFooterLinkClass());

        $box = new Components\Widget\SmallBox(null, null, null, 'primary');
        $this->assertStringContainsString('link-light', $box->makeFooterLinkClass());

        // An unthemed box keeps the light link of the reference layouts.

        $box = new Components\Widget\SmallBox();
        $this->assertStringContainsString('link-light', $box->makeFooterLinkClass());
    }

    public function testSmallBoxFooterLinkKeepsTheUnderlineSuppression()
    {
        $box = new Components\Widget\SmallBox(null, null, null, 'info');
        $classes = $box->makeFooterLinkClass();

        $this->assertStringContainsString('small-box-footer', $classes);
        $this->assertStringContainsString('link-underline-opacity-0', $classes);
        $this->assertStringContainsString('link-underline-opacity-50-hover', $classes);
    }

    public function testSmallBoxFooterLinkWithTheV3Aliases()
    {
        config([
            'adminlte.assets.extended_colors' => true,
            'adminlte.assets.extended_colors_v3_aliases' => true,
        ]);

        $box = new Components\Widget\SmallBox(null, null, null, 'yellow');
        $this->assertStringContainsString('link-dark', $box->makeFooterLinkClass());

        $box = new Components\Widget\SmallBox(null, null, null, 'maroon');
        $this->assertStringContainsString('link-light', $box->makeFooterLinkClass());
    }

    public function testProgressBarUsesTheContrastAwareBackground()
    {
        // The reference emits 'text-bg-*', which keeps the label readable over
        // the light backgrounds.

        $progress = new Components\Widget\Progress(null, 'warning');

        $this->assertStringContainsString(
            'text-bg-warning',
            $progress->makeProgressBarClass()
        );
    }
}
