<?php

require_once __DIR__.'/ComponentTestHelpers.php';

use Illuminate\Support\Facades\Blade;
use JeroenNoten\LaravelAdminLte\View\Components;

class NavbarDropdownTest extends TestCase
{
    use ComponentTestHelpers;

    /**
     * Register the navbar dropdown components. They are provided by the
     * package service provider on a real application, the aliases are set up
     * here so the templates of this test case can be rendered in isolation.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $components = [
            'adminlte-navbar-dropdown' => Components\Layout\NavbarDropdown::class,
            'adminlte-navbar-dropdown-item' => Components\Layout\NavbarDropdownItem::class,
            'adminlte-navbar-custom-menu' => Components\Layout\NavbarCustomMenu::class,
        ];

        foreach ($components as $alias => $class) {
            Blade::component($class, $alias);
        }
    }

    /**
     * Return array with the available blade components.
     */
    protected function getComponents()
    {
        $base = 'adminlte::components.layout';

        return [
            "{$base}.navbar-dropdown" => new Components\Layout\NavbarDropdown(),
            "{$base}.navbar-dropdown-item" => new Components\Layout\NavbarDropdownItem(),
            "{$base}.navbar-custom-menu" => new Components\Layout\NavbarCustomMenu(),
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

    public function testAllComponentsRenderFreeOfLegacyMarkup()
    {
        $templates = [
            '<x-adminlte-navbar-custom-menu nav-class="ms-auto">
                <x-adminlte-navbar-dropdown id="msg" icon="bi bi-chat-text"
                    icon-theme="lightblue" text="Messages" label="Messages"
                    badge="3" badge-theme="danger" size="xl" align="start"
                    animated caret header="3 Messages" footer="See all"
                    footer-url="/messages" menu-class="shadow">
                    <x-adminlte-navbar-dropdown-item title="Brad Diesel"
                        img="/img/u1.png" text="Call me whenever you can..."
                        time="4 Hours Ago" marker="bi bi-star-fill"
                        marker-theme="maroon" url="/m/1" divider/>
                    <x-adminlte-navbar-dropdown-item icon="bi bi-envelope"
                        icon-theme="purple" text="4 new messages" time="3 mins"/>
                </x-adminlte-navbar-dropdown>
            </x-adminlte-navbar-custom-menu>',
            '<x-adminlte-navbar-dropdown/>',
        ];

        foreach ($templates as $template) {
            $html = $this->renderComponent($template);

            $this->assertV4Markup($html);
            $this->assertV4Markup($this->renderPushedAssets());

            // The layout components are fully jQuery free on AdminLTE v4.

            $this->assertFreeOfJquery($html);
            $this->assertFreeOfJquery($this->renderPushedAssets());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Navbar dropdown component tests.
    |--------------------------------------------------------------------------
    */

    public function testNavbarDropdownDefaultClasses()
    {
        $component = new Components\Layout\NavbarDropdown();

        $this->assertEquals('nav-item dropdown', $component->makeListItemClass());
        $this->assertEquals('nav-link', $component->makeToggleClass());
        $this->assertEquals('navbar-badge badge', $component->makeBadgeClass());

        // The reference navbar dropdowns are large and aligned to the end.

        $this->assertEquals(
            'dropdown-menu dropdown-menu-lg dropdown-menu-end',
            $component->makeMenuClass()
        );
    }

    public function testNavbarDropdownAdvancedClasses()
    {
        // $id, $icon, $iconTheme, $text, $label, $badge, $badgeTheme, $size,
        // $align, $animated, $caret, $header, $footer, $footerUrl, $menuClass

        $component = new Components\Layout\NavbarDropdown(
            'msg', 'bi bi-chat-text', 'danger', null, 'Messages', '3',
            'primary', 'xl', 'start', true, true, null, null, null, 'shadow'
        );

        $this->assertEquals(
            'bi bi-chat-text text-danger',
            $component->makeIconClass()
        );

        $this->assertEquals(
            'navbar-badge badge text-bg-primary',
            $component->makeBadgeClass()
        );

        $this->assertEquals(
            'dropdown-menu dropdown-menu-xl dropdown-menu-start animated-dropdown-menu shadow',
            $component->makeMenuClass()
        );
    }

    public function testNavbarDropdownMenuSizeIsResolved()
    {
        $makeSize = function ($size) {
            $component = new Components\Layout\NavbarDropdown(
                null, null, null, null, null, null, null, $size
            );

            return $component->makeMenuClass();
        };

        // Both sizes provided by the AdminLTE v4 stylesheet are supported.

        $this->assertStringContainsString('dropdown-menu-lg', $makeSize('lg'));
        $this->assertStringContainsString('dropdown-menu-xl', $makeSize('xl'));
        $this->assertStringContainsString('dropdown-menu-xl', $makeSize(' XL '));

        // An unknown size leaves the menu on the default Bootstrap width, so
        // no arbitrary class can ever reach the generated markup.

        $this->assertEquals(
            'dropdown-menu dropdown-menu-end',
            $makeSize('none')
        );

        $this->assertEquals(
            'dropdown-menu dropdown-menu-end',
            $makeSize(false)
        );

        $this->assertEquals(
            'dropdown-menu dropdown-menu-end',
            $makeSize(['xl'])
        );

        // Note the size defaults to the large one, the one used by every
        // navbar dropdown of the AdminLTE v4 reference layouts.

        $component = new Components\Layout\NavbarDropdown();
        $this->assertEquals('lg', $component->size);
        $this->assertStringContainsString('dropdown-menu-lg', $makeSize(null));
    }

    public function testNavbarDropdownMenuAlignmentIsResolved()
    {
        $makeAlign = function ($align) {
            $component = new Components\Layout\NavbarDropdown(
                null, null, null, null, null, null, null, null, $align
            );

            return $component->align;
        };

        $this->assertEquals('start', $makeAlign('start'));
        $this->assertEquals('end', $makeAlign('end'));
        $this->assertEquals('end', $makeAlign(' END '));

        // Any other value falls back to the default alignment.

        $this->assertEquals('end', $makeAlign('middle'));
        $this->assertEquals('end', $makeAlign(null));
        $this->assertEquals('end', $makeAlign(66));
    }

    public function testNavbarDropdownCaretClasses()
    {
        // Without the caret, the toggle is a plain navbar link, like the ones
        // used by the AdminLTE v4 reference layouts.

        $component = new Components\Layout\NavbarDropdown(
            null, 'bi bi-bell', null, null, null, null, null, null, null,
            null, false
        );

        $this->assertEquals('nav-link', $component->makeToggleClass());

        // The '.dropdown-icon' class only drops the left margin of the caret,
        // so it is added on an icon only toggle.

        $component = new Components\Layout\NavbarDropdown(
            null, 'bi bi-bell', null, null, null, null, null, null, null,
            null, true
        );

        $this->assertEquals(
            'nav-link dropdown-toggle dropdown-icon',
            $component->makeToggleClass()
        );

        // A toggle holding a visible text keeps the Bootstrap caret spacing.

        $component = new Components\Layout\NavbarDropdown(
            null, 'bi bi-bell', null, 'Alerts', null, null, null, null, null,
            null, true
        );

        $this->assertEquals(
            'nav-link dropdown-toggle',
            $component->makeToggleClass()
        );
    }

    public function testNavbarDropdownResolvesTheV3ThemeColors()
    {
        config(['adminlte.assets.extended_colors_v3_aliases' => false]);

        $component = new Components\Layout\NavbarDropdown(
            null, 'bi bi-bell', 'lightblue', null, null, '3', 'maroon'
        );

        $this->assertStringContainsString('text-sky', $component->makeIconClass());
        $this->assertStringContainsString('text-bg-pink', $component->makeBadgeClass());

        // When the v3 alias stylesheet is in use, the old names are real CSS
        // classes, so they are kept untouched.

        config(['adminlte.assets.extended_colors_v3_aliases' => true]);

        $component = new Components\Layout\NavbarDropdown(
            null, 'bi bi-bell', 'lightblue', null, null, '3', 'maroon'
        );

        $this->assertStringContainsString('text-lightblue', $component->makeIconClass());
        $this->assertStringContainsString('text-bg-maroon', $component->makeBadgeClass());
    }

    public function testNavbarDropdownToggleId()
    {
        // The toggle id is derived from the id of the wrapper.

        $component = new Components\Layout\NavbarDropdown('msg');
        $this->assertEquals('msg-toggle', $component->toggleId);

        // An id holding unsafe characters is sanitized before being used.

        $component = new Components\Layout\NavbarDropdown('a b"c<d');
        $this->assertEquals('abcd-toggle', $component->toggleId);

        // Without a wrapper id, a unique one is generated, since the dropdown
        // menu always refers to its toggle.

        $component = new Components\Layout\NavbarDropdown();

        $this->assertStringStartsWith(
            'adminlte-navbar-dropdown-',
            $component->toggleId
        );

        $other = new Components\Layout\NavbarDropdown();
        $this->assertNotEquals($component->toggleId, $other->toggleId);
    }

    public function testNavbarDropdownRendersEveryAttribute()
    {
        $html = $this->renderComponent(
            '<x-adminlte-navbar-dropdown id="msg" icon="bi bi-chat-text"
                icon-theme="danger" text="Messages" label="Messages: 3 unread"
                badge="3" badge-theme="primary" size="xl" align="start"
                animated caret header="3 Messages" footer="See All Messages"
                footer-url="/messages" menu-class="shadow">
                <span class="item">The items</span>
            </x-adminlte-navbar-dropdown>'
        );

        $this->assertStringContainsString('class="nav-item dropdown"', $html);
        $this->assertStringContainsString('id="msg"', $html);
        $this->assertStringContainsString('id="msg-toggle"', $html);
        $this->assertStringContainsString('class="nav-link dropdown-toggle"', $html);
        $this->assertStringContainsString('data-bs-toggle="dropdown"', $html);
        $this->assertStringContainsString('aria-label="Messages: 3 unread"', $html);
        $this->assertStringContainsString('bi bi-chat-text text-danger', $html);
        $this->assertStringContainsString('Messages', $html);
        $this->assertStringContainsString(
            '<span class="navbar-badge badge text-bg-primary">3</span>',
            $html
        );
        $this->assertStringContainsString(
            'class="dropdown-menu dropdown-menu-xl dropdown-menu-start animated-dropdown-menu shadow"',
            $html
        );
        $this->assertStringContainsString('aria-labelledby="msg-toggle"', $html);
        $this->assertStringContainsString('class="dropdown-item dropdown-header"', $html);
        $this->assertStringContainsString('3 Messages', $html);
        $this->assertStringContainsString('<div class="dropdown-divider"></div>', $html);
        $this->assertStringContainsString('<span class="item">The items</span>', $html);
        $this->assertStringContainsString(
            '<a href="/messages" class="dropdown-item dropdown-footer">',
            $html
        );
        $this->assertStringContainsString('See All Messages', $html);

        $this->assertV4Markup($html);
    }

    public function testNavbarDropdownWithoutOptionalPropsIsMinimal()
    {
        $html = $this->renderComponent('<x-adminlte-navbar-dropdown/>');

        $this->assertStringContainsString('class="nav-item dropdown"', $html);
        $this->assertStringContainsString(
            'class="dropdown-menu dropdown-menu-lg dropdown-menu-end"',
            $html
        );

        // No icon, badge, header, footer or divider is emitted.

        $this->assertStringNotContainsString('<i ', $html);
        $this->assertStringNotContainsString('navbar-badge', $html);
        $this->assertStringNotContainsString('dropdown-header', $html);
        $this->assertStringNotContainsString('dropdown-footer', $html);
        $this->assertStringNotContainsString('dropdown-divider', $html);
        $this->assertStringNotContainsString('aria-label=', $html);
        $this->assertStringNotContainsString('animated-dropdown-menu', $html);

        // The wrapper gets no empty id attribute when none was provided.

        $this->assertStringNotContainsString('id=""', $html);
    }

    public function testNavbarDropdownSlotsTakePrecedenceOverTheAttributes()
    {
        $html = $this->renderComponent(
            '<x-adminlte-navbar-dropdown header="The header" footer="The footer">
                <x-slot name="headerSlot"><b>Slotted header</b></x-slot>
                <x-slot name="footerSlot"><b>Slotted footer</b></x-slot>
            </x-adminlte-navbar-dropdown>'
        );

        $this->assertStringContainsString('<b>Slotted header</b>', $html);
        $this->assertStringContainsString('<b>Slotted footer</b>', $html);
        $this->assertStringNotContainsString('The header', $html);
        $this->assertStringNotContainsString('The footer', $html);
    }

    public function testNavbarDropdownFooterUrlDefaultsToAnAnchor()
    {
        $html = $this->renderComponent(
            '<x-adminlte-navbar-dropdown footer="See all"/>'
        );

        $this->assertStringContainsString(
            '<a href="#" class="dropdown-item dropdown-footer">',
            $html
        );
    }

    public function testNavbarDropdownForwardsTheExtraAttributes()
    {
        $html = $this->renderComponent(
            '<x-adminlte-navbar-dropdown class="user-menu" data-test="1"
                title="A tooltip"/>'
        );

        // The extra classes are merged with the ones of the component.

        $this->assertMatchesRegularExpression(
            '/class="[^"]*nav-item dropdown[^"]*user-menu[^"]*"/',
            $html
        );

        $this->assertStringContainsString('data-test="1"', $html);
        $this->assertStringContainsString('title="A tooltip"', $html);
    }

    public function testNavbarDropdownEscapesTheTextProperties()
    {
        $html = $this->renderComponent(
            '<x-adminlte-navbar-dropdown text="<b>T</b>" label="<b>L</b>"
                badge="<b>B</b>" header="<b>H</b>" footer="<b>F</b>"/>'
        );

        $this->assertStringNotContainsString('<b>', $html);

        foreach (['T', 'L', 'B', 'H', 'F'] as $value) {
            $this->assertStringContainsString("&lt;b&gt;{$value}&lt;/b&gt;", $html);
        }
    }

    public function testNavbarDropdownAccessibilityAttributes()
    {
        $html = $this->renderComponent(
            '<x-adminlte-navbar-dropdown id="msg" icon="bi bi-chat-text"
                label="Messages" badge="3"/>'
        );

        // The toggle is a button-like control that names itself and exposes
        // its expanded state, and the menu points back at it.

        $this->assertStringContainsString('role="button"', $html);
        $this->assertStringContainsString('aria-expanded="false"', $html);
        $this->assertStringContainsString('aria-label="Messages"', $html);
        $this->assertStringContainsString('aria-labelledby="msg-toggle"', $html);

        // The icon is decorative, the label already names the control.

        $this->assertStringContainsString(
            '<i class="bi bi-chat-text" aria-hidden="true"></i>',
            $html
        );
    }

    public function testNavbarDropdownRegistersTheAnimationBridgeOnce()
    {
        $this->renderComponent(
            '<x-adminlte-navbar-dropdown id="a" animated/>
             <x-adminlte-navbar-dropdown id="b" animated/>'
        );

        $js = $this->renderPushedAssets();

        // The bridge mirrors the Bootstrap 5 dropdown state into the '.open'
        // class the AdminLTE animation is keyed on.

        $this->assertStringContainsString('show.bs.dropdown', $js);
        $this->assertStringContainsString('hidden.bs.dropdown', $js);
        $this->assertStringContainsString('animated-dropdown-menu', $js);
        $this->assertStringContainsString("classList.toggle('open'", $js);

        $this->assertEquals(1, substr_count($js, 'const syncOpenState'));

        // Every lookup of the bridge is null guarded and no jQuery is used.

        $this->assertStringContainsString('if (! wrapper ||', $js);
        $this->assertFreeOfJquery($js);
    }

    public function testNavbarDropdownWithoutAnimationRegistersNoScript()
    {
        $this->renderComponent('<x-adminlte-navbar-dropdown id="a"/>');

        $this->assertStringNotContainsString(
            'syncOpenState',
            $this->renderPushedAssets()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Navbar dropdown item component tests.
    |--------------------------------------------------------------------------
    */

    public function testNavbarDropdownItemClasses()
    {
        $component = new Components\Layout\NavbarDropdownItem();

        $attrs = $component->makeAnchorDefaultAttrs();

        $this->assertEquals('dropdown-item', $attrs['class']);
        $this->assertEquals('#', $attrs['href']);
        $this->assertEquals('img-size-50 rounded-circle me-3', $component->makeImageClass());
        $this->assertFalse($component->isMediaItem());
        $this->assertFalse($component->divider);

        // $title, $text, $time, $icon, $iconTheme, $img, $imgAlt, $marker,
        // $markerTheme, $url, $divider

        $component = new Components\Layout\NavbarDropdownItem(
            'Brad Diesel', 'Call me', '4 Hours Ago', 'bi bi-envelope',
            'lightblue', '/img/u1.png', 'Brad', 'bi bi-star-fill', 'maroon',
            '/m/1', true
        );

        $this->assertTrue($component->isMediaItem());
        $this->assertTrue($component->divider);
        $this->assertEquals('/m/1', $component->makeAnchorDefaultAttrs()['href']);
        $this->assertEquals('bi bi-envelope me-2 text-sky', $component->makeIconClass());
        $this->assertEquals('float-end fs-7 text-pink', $component->makeMarkerClass());
    }

    public function testNavbarDropdownItemMediaLayoutIsUsedWithATitleOrAnImage()
    {
        $withTitle = new Components\Layout\NavbarDropdownItem('A title');
        $withImage = new Components\Layout\NavbarDropdownItem(
            null, null, null, null, null, '/img/u1.png'
        );
        $withText = new Components\Layout\NavbarDropdownItem(null, 'A text');

        $this->assertTrue($withTitle->isMediaItem());
        $this->assertTrue($withImage->isMediaItem());
        $this->assertFalse($withText->isMediaItem());
    }

    public function testNavbarDropdownItemRendersTheMediaLayout()
    {
        $html = $this->renderComponent(
            '<x-adminlte-navbar-dropdown-item title="Brad Diesel"
                img="/img/u1.png" img-alt="Brad" text="Call me whenever"
                time="4 Hours Ago" marker="bi bi-star-fill"
                marker-theme="danger" url="/m/1" divider/>'
        );

        $this->assertStringContainsString(
            '<a class="dropdown-item" href="/m/1">',
            $html
        );
        $this->assertStringContainsString('<div class="d-flex">', $html);
        $this->assertStringContainsString(
            '<img src="/img/u1.png" alt="Brad" class="img-size-50 rounded-circle me-3">',
            $html
        );
        $this->assertStringContainsString('<p class="dropdown-item-title">', $html);
        $this->assertStringContainsString('Brad Diesel', $html);
        $this->assertStringContainsString('class="float-end fs-7 text-danger"', $html);
        $this->assertStringContainsString(
            '<i class="bi bi-star-fill" aria-hidden="true"></i>',
            $html
        );
        $this->assertStringContainsString('<p class="fs-7">Call me whenever</p>', $html);
        $this->assertStringContainsString('<p class="fs-7 text-secondary">', $html);
        $this->assertStringContainsString(
            '<i class="bi bi-clock-fill me-1" aria-hidden="true"></i> 4 Hours Ago',
            $html
        );
        $this->assertStringContainsString('<div class="dropdown-divider"></div>', $html);

        $this->assertV4Markup($html);
    }

    public function testNavbarDropdownItemRendersTheInlineLayout()
    {
        $html = $this->renderComponent(
            '<x-adminlte-navbar-dropdown-item icon="bi bi-envelope"
                icon-theme="info" text="4 new messages" time="3 mins"/>'
        );

        $this->assertStringContainsString(
            '<i class="bi bi-envelope me-2 text-info" aria-hidden="true"></i>',
            $html
        );
        $this->assertStringContainsString('4 new messages', $html);
        $this->assertStringContainsString(
            '<span class="float-end text-secondary fs-7">3 mins</span>',
            $html
        );

        // The inline layout holds no media markup at all.

        $this->assertStringNotContainsString('d-flex', $html);
        $this->assertStringNotContainsString('dropdown-item-title', $html);
        $this->assertStringNotContainsString('dropdown-divider', $html);

        $this->assertV4Markup($html);
    }

    public function testNavbarDropdownItemImageAltDefaultsToAnEmptyString()
    {
        $html = $this->renderComponent(
            '<x-adminlte-navbar-dropdown-item title="Brad" img="/img/u1.png"/>'
        );

        // The image of a message item is decorative, the title names it.

        $this->assertStringContainsString('alt=""', $html);
    }

    public function testNavbarDropdownItemSlotTakesPrecedenceOverTheAttributes()
    {
        $html = $this->renderComponent(
            '<x-adminlte-navbar-dropdown-item title="A title" text="A text"
                icon="bi bi-envelope">
                <span class="custom">Custom content</span>
            </x-adminlte-navbar-dropdown-item>'
        );

        $this->assertStringContainsString('<span class="custom">Custom content</span>', $html);
        $this->assertStringNotContainsString('A title', $html);
        $this->assertStringNotContainsString('A text', $html);
        $this->assertStringNotContainsString('dropdown-item-title', $html);
    }

    public function testNavbarDropdownItemForwardsTheExtraAttributes()
    {
        $html = $this->renderComponent(
            '<x-adminlte-navbar-dropdown-item text="A text" class="active"
                data-test="1"/>'
        );

        $this->assertMatchesRegularExpression(
            '/class="[^"]*dropdown-item[^"]*active[^"]*"/',
            $html
        );

        $this->assertStringContainsString('data-test="1"', $html);

        // A href set by the user wins over the one built from the url.

        $html = $this->renderComponent(
            '<x-adminlte-navbar-dropdown-item text="A text" url="/a" href="/b"/>'
        );

        $this->assertStringContainsString('href="/b"', $html);
        $this->assertStringNotContainsString('href="/a"', $html);
    }

    public function testNavbarDropdownItemEscapesTheTextProperties()
    {
        $html = $this->renderComponent(
            '<x-adminlte-navbar-dropdown-item title="<b>T</b>" text="<b>X</b>"
                time="<b>M</b>" img="/img/u1.png" img-alt="<b>A</b>"/>'
        );

        $this->assertStringNotContainsString('<b>', $html);

        foreach (['T', 'X', 'M'] as $value) {
            $this->assertStringContainsString("&lt;b&gt;{$value}&lt;/b&gt;", $html);
        }

        $this->assertStringContainsString('alt="&lt;b&gt;A&lt;/b&gt;"', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Navbar custom menu component tests.
    |--------------------------------------------------------------------------
    */

    public function testNavbarCustomMenuClasses()
    {
        $component = new Components\Layout\NavbarCustomMenu();

        $this->assertEquals('navbar-custom-menu', $component->makeWrapperClass());
        $this->assertEquals('navbar-nav', $component->makeNavClass());

        $component = new Components\Layout\NavbarCustomMenu('ms-auto');

        $this->assertEquals('navbar-nav ms-auto', $component->makeNavClass());
    }

    public function testNavbarCustomMenuRendersTheWrapper()
    {
        $html = $this->renderComponent(
            '<x-adminlte-navbar-custom-menu nav-class="ms-auto" id="topbar"
                data-test="1">
                <li class="nav-item">An item</li>
            </x-adminlte-navbar-custom-menu>'
        );

        $this->assertStringContainsString('class="navbar-custom-menu"', $html);
        $this->assertStringContainsString('id="topbar"', $html);
        $this->assertStringContainsString('data-test="1"', $html);
        $this->assertStringContainsString('<ul class="navbar-nav ms-auto">', $html);
        $this->assertStringContainsString('<li class="nav-item">An item</li>', $html);

        $this->assertV4Markup($html);
    }

    /*
    |--------------------------------------------------------------------------
    | Composition tests.
    |--------------------------------------------------------------------------
    */

    public function testNavbarDropdownHoldsItsItemsInsideTheMenu()
    {
        $html = $this->renderComponent(
            '<x-adminlte-navbar-dropdown id="msg" icon="bi bi-chat-text"
                label="Messages" header="3 Messages" footer="See all">
                <x-adminlte-navbar-dropdown-item title="Brad Diesel"
                    img="/img/u1.png" text="Call me" time="4 Hours Ago" divider/>
                <x-adminlte-navbar-dropdown-item icon="bi bi-envelope"
                    text="4 new messages" time="3 mins"/>
            </x-adminlte-navbar-dropdown>'
        );

        $menuPos = strpos($html, 'dropdown-menu-lg');
        $headerPos = strpos($html, 'dropdown-header');
        $firstItemPos = strpos($html, 'Brad Diesel');
        $secondItemPos = strpos($html, '4 new messages');
        $footerPos = strpos($html, 'dropdown-footer');

        $this->assertLessThan($headerPos, $menuPos);
        $this->assertLessThan($firstItemPos, $headerPos);
        $this->assertLessThan($secondItemPos, $firstItemPos);
        $this->assertLessThan($footerPos, $secondItemPos);

        // The header, the item and the footer dividers are all emitted.

        $this->assertEquals(3, substr_count($html, 'class="dropdown-divider"'));

        $this->assertV4Markup($html);
    }
}
