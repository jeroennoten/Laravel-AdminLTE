<?php

require_once __DIR__.'/ComponentTestHelpers.php';

use JeroenNoten\LaravelAdminLte\View\Components;

class CardExtensionsTest extends TestCase
{
    use ComponentTestHelpers;

    /**
     * Collapse every run of whitespace of the provided markup into a single
     * space, so the assertions do not depend on how the template wraps the
     * attributes of an element.
     *
     * @param  string  $html  The rendered markup
     * @return string
     */
    protected function squish($html)
    {
        return trim(preg_replace('/\s+/', ' ', $html));
    }

    /*
    |--------------------------------------------------------------------------
    | Header slot tests.
    |--------------------------------------------------------------------------
    */

    public function testCardHeaderSlotReplacesTheWholeHeaderContent()
    {
        $html = $this->renderComponent(
            '<x-adminlte-card title="Ignored" icon="bi bi-bell" collapsible>'.
            'Body'.
            '<x-slot name="headerSlot"><span id="custom">Custom</span></x-slot>'.
            '</x-adminlte-card>'
        );

        // The header container is still rendered, but its content comes
        // entirely from the slot.

        $this->assertStringContainsString('class="card-header"', $html);
        $this->assertStringContainsString('<span id="custom">Custom</span>', $html);

        $this->assertStringNotContainsString('card-title', $html);
        $this->assertStringNotContainsString('card-tools', $html);
        $this->assertStringNotContainsString('Ignored', $html);
        $this->assertStringNotContainsString('data-lte-toggle="card-collapse"', $html);

        $this->assertV4Markup($html);
    }

    public function testCardHeaderSlotAloneRendersTheHeader()
    {
        // A card without title, icon and tools is headerless, unless the
        // header slot is provided.

        $component = new Components\Widget\Card();

        $this->assertTrue($component->isCardHeaderEmpty());
        $this->assertFalse($component->isCardHeaderEmpty(false, false, true));

        $html = $this->renderComponent(
            '<x-adminlte-card><x-slot name="headerSlot">H</x-slot></x-adminlte-card>'
        );

        $this->assertStringContainsString('class="card-header"', $html);
    }

    public function testCardHeaderSlotKeepsTheHeaderClassAttribute()
    {
        $html = $this->renderComponent(
            '<x-adminlte-card header-class="h-cls">'.
            '<x-slot name="headerSlot">H</x-slot></x-adminlte-card>'
        );

        $this->assertStringContainsString('class="card-header h-cls"', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Title slot tests.
    |--------------------------------------------------------------------------
    */

    public function testCardTitleSlotReplacesTheTitle()
    {
        $html = $this->renderComponent(
            '<x-adminlte-card title="Ignored">'.
            'Body'.
            '<x-slot name="titleSlot"><a href="/u">Linked</a></x-slot>'.
            '</x-adminlte-card>'
        );

        $this->assertStringContainsString('<h3 class="card-title">', $html);
        $this->assertStringContainsString('<a href="/u">Linked</a>', $html);
        $this->assertStringNotContainsString('Ignored', $html);

        // The tools container is still available next to the custom title.

        $this->assertStringContainsString('class="card-tools"', $html);

        $this->assertV4Markup($html);
    }

    public function testCardTitleSlotKeepsTheIcon()
    {
        // The icon is an attribute of its own, so it is not swallowed by the
        // title slot.

        $html = $this->renderComponent(
            '<x-adminlte-card icon="bi bi-bell">'.
            '<x-slot name="titleSlot">Custom</x-slot></x-adminlte-card>'
        );

        $this->assertStringContainsString('<i class="bi bi-bell me-1"', $html);
        $this->assertStringContainsString('Custom', $html);
    }

    public function testCardTitleSlotAloneRendersTheHeader()
    {
        $component = new Components\Widget\Card();

        $this->assertTrue($component->isCardHeaderEmpty());
        $this->assertFalse($component->isCardHeaderEmpty(false, true));

        $html = $this->renderComponent(
            '<x-adminlte-card><x-slot name="titleSlot">T</x-slot></x-adminlte-card>'
        );

        $this->assertStringContainsString('class="card-header"', $html);
        $this->assertStringContainsString('class="card-title"', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Title class tests.
    |--------------------------------------------------------------------------
    */

    public function testCardTitleClassAttribute()
    {
        $component = new Components\Widget\Card(
            'title', null, null, null, null, null, null, null, null, null,
            null, 'text-uppercase fw-bold'
        );

        $this->assertEquals(
            'card-title text-uppercase fw-bold',
            $component->makeCardTitleClass()
        );
    }

    public function testCardTitleClassRendersOnTheTitleElement()
    {
        $html = $this->renderComponent(
            '<x-adminlte-card title="T" title-class="text-uppercase"/>'
        );

        $this->assertStringContainsString(
            '<h3 class="card-title text-uppercase">', $html
        );
    }

    public function testCardTitleClassDefaultsToThePlainTitle()
    {
        // Backward compatibility: without the attribute the title class is
        // still the plain 'card-title' one.

        $component = new Components\Widget\Card('title');

        $this->assertEquals('card-title', $component->makeCardTitleClass());
    }

    /*
    |--------------------------------------------------------------------------
    | Title tag tests.
    |--------------------------------------------------------------------------
    */

    public function testCardTitleTagDefaultsToH3()
    {
        $component = new Components\Widget\Card('title');

        $this->assertEquals('h3', $component->titleTag);

        $html = $this->renderComponent('<x-adminlte-card title="T"/>');

        $this->assertStringContainsString('<h3 class="card-title">', $html);
        $this->assertStringContainsString('</h3>', $html);
    }

    public function testCardTitleTagAcceptsTheAllowedTags()
    {
        $allowed = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span'];

        foreach ($allowed as $tag) {
            $component = new Components\Widget\Card(
                'title', null, null, null, null, null, null, null, null, null,
                null, null, $tag
            );

            $this->assertEquals($tag, $component->titleTag);

            $html = $this->renderComponent(
                "<x-adminlte-card title=\"T\" title-tag=\"{$tag}\"/>"
            );

            $this->assertStringContainsString("<{$tag} class=\"card-title\">", $html);
            $this->assertStringContainsString("</{$tag}>", $html);
        }
    }

    public function testCardTitleTagIsCaseInsensitiveAndTrimmed()
    {
        $component = new Components\Widget\Card(
            'title', null, null, null, null, null, null, null, null, null,
            null, null, ' H2 '
        );

        $this->assertEquals('h2', $component->titleTag);
    }

    public function testCardTitleTagRejectsAnyOtherValue()
    {
        // Rejection path: an unknown or hostile tag never reaches the markup,
        // the default tag is used instead.

        $rejected = [
            'script', 'p', 'h7', 'a', '', '  ', 'h3 onclick="x()"',
            'span><script>alert(1)</script><span', 0, true, ['h1'],
        ];

        foreach ($rejected as $tag) {
            $component = new Components\Widget\Card(
                'title', null, null, null, null, null, null, null, null, null,
                null, null, $tag
            );

            $this->assertEquals('h3', $component->titleTag);
        }

        $html = $this->renderComponent(
            '<x-adminlte-card title="T" title-tag="script"/>'
        );

        $this->assertStringContainsString('<h3 class="card-title">', $html);
        $this->assertStringNotContainsString('<script', $html);
    }

    public function testCardTitleTagDoesNotInterpolateAnInjectedTag()
    {
        $html = $this->renderComponent(
            '<x-adminlte-card title="T" title-tag=\'span onclick="alert(1)"\'/>'
        );

        $this->assertStringNotContainsString('onclick', $html);
        $this->assertStringContainsString('<h3 class="card-title">', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Card tabs tests.
    |--------------------------------------------------------------------------
    */

    /**
     * Return a blade template of a tabbed card with two tabs.
     *
     * @param  string  $attributes  Extra attributes for the card
     * @return string
     */
    protected function makeTabbedCardTemplate($attributes = '')
    {
        return '<x-adminlte-card '.$attributes.' :tabs="[
                [\'id\' => \'tab-1\', \'label\' => \'Tab 1\'],
                [\'id\' => \'tab-2\', \'label\' => \'Tab 2\'],
            ]">
            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" tabindex="0">One</div>
            <div class="tab-pane fade" id="tab-2" role="tabpanel" tabindex="0">Two</div>
        </x-adminlte-card>';
    }

    public function testCardTabsRenderTheReferenceMarkup()
    {
        $html = $this->renderComponent(
            $this->makeTabbedCardTemplate('theme="primary"')
        );

        // The card and the header follow the AdminLTE v4 reference markup for
        // a tabbed card.

        $this->assertStringContainsString('card mb-4 card-primary card-tabs', $html);
        $this->assertStringContainsString('class="card-header p-0 pt-1"', $html);
        $this->assertStringContainsString('<ul class="nav nav-tabs" role="tablist">', $html);
        $this->assertStringContainsString('<li class="nav-item" role="presentation">', $html);
        $this->assertStringContainsString('data-bs-toggle="pill"', $html);
        $this->assertStringContainsString('data-bs-target="#tab-1"', $html);
        $this->assertStringContainsString('data-bs-target="#tab-2"', $html);
        $this->assertStringContainsString('role="tab"', $html);

        // The body wraps the panes into a 'tab-content' container.

        $this->assertStringContainsString('class="card-body"', $html);
        $this->assertStringContainsString('<div class="tab-content">', $html);
        $this->assertStringContainsString('id="tab-1"', $html);

        // A tabbed card has no title.

        $this->assertStringNotContainsString('card-title', $html);

        $this->assertV4Markup($html);
    }

    public function testCardTabsActivateTheFirstTabByDefault()
    {
        $html = $this->renderComponent($this->makeTabbedCardTemplate());

        $this->assertMatchesRegularExpression(
            '/class="nav-link active"\s+data-bs-toggle="pill"\s+data-bs-target="#tab-1"/',
            $html
        );

        $this->assertMatchesRegularExpression(
            '/class="nav-link"\s+data-bs-toggle="pill"\s+data-bs-target="#tab-2"/',
            $html
        );
    }

    public function testCardTabsHonorTheActiveFlag()
    {
        $component = new Components\Widget\Card(
            null, null, null, null, null, null, null, null, null, null, null,
            null, null,
            [
                ['id' => 'a', 'label' => 'A'],
                ['id' => 'b', 'label' => 'B', 'active' => true],
                ['id' => 'c', 'label' => 'C', 'active' => true],
            ]
        );

        // Only the first tab flagged as active stays active.

        $this->assertFalse($component->tabs[0]['active']);
        $this->assertTrue($component->tabs[1]['active']);
        $this->assertFalse($component->tabs[2]['active']);

        $this->assertEquals('nav-link', $component->makeTabLinkClass($component->tabs[0]));
        $this->assertEquals('nav-link active', $component->makeTabLinkClass($component->tabs[1]));
    }

    public function testCardTabsRenderTheAriaAttributes()
    {
        $html = $this->renderComponent($this->makeTabbedCardTemplate());

        $this->assertStringContainsString('aria-controls="tab-1"', $html);
        $this->assertStringContainsString('aria-controls="tab-2"', $html);
        $this->assertStringContainsString('aria-selected="true"', $html);
        $this->assertStringContainsString('aria-selected="false"', $html);
    }

    public function testCardTabsAcceptShorthandAndKeyedEntries()
    {
        $component = new Components\Widget\Card(
            null, null, null, null, null, null, null, null, null, null, null,
            null, null,
            ['home' => 'Home', ['label' => 'Away']]
        );

        $this->assertEquals('home', $component->tabs[0]['id']);
        $this->assertEquals('Home', $component->tabs[0]['label']);
        $this->assertTrue($component->tabs[0]['active']);

        // An entry without id gets a generated one.

        $this->assertEquals('card-tab-2', $component->tabs[1]['id']);
        $this->assertEquals('Away', $component->tabs[1]['label']);
    }

    public function testCardTabsSanitizeTheTabIdentifiers()
    {
        $component = new Components\Widget\Card(
            null, null, null, null, null, null, null, null, null, null, null,
            null, null,
            [
                ['id' => 'a b"><script>', 'label' => 'A'],
                ['id' => '"><img>', 'label' => 'B'],
            ]
        );

        $this->assertEquals('abscript', $component->tabs[0]['id']);

        // An identifier left empty by the sanitizer gets a generated one.

        $this->assertEquals('img', $component->tabs[1]['id']);

        $html = $this->renderComponent(
            '<x-adminlte-card :tabs="[[\'id\' => \'\\\'&quot;&gt;&lt;b&gt;\', \'label\' => \'X\']]">P</x-adminlte-card>'
        );

        $this->assertStringNotContainsString('<b>', $html);
        $this->assertStringContainsString('data-bs-target="#b"', $html);
    }

    public function testCardTabsRenderTheTabIcons()
    {
        $html = $this->renderComponent(
            '<x-adminlte-card :tabs="[
                [\'id\' => \'t1\', \'label\' => \'One\', \'icon\' => \'bi bi-bell\'],
            ]">Pane</x-adminlte-card>'
        );

        $this->assertStringContainsString('<i class="bi bi-bell me-1"', $html);
    }

    public function testCardOutlineTabsThemeMode()
    {
        $html = $this->renderComponent(
            $this->makeTabbedCardTemplate('theme="teal" theme-mode="outline"')
        );

        $this->assertStringContainsString(
            'card mb-4 card-teal card-outline card-tabs card-outline-tabs',
            $html
        );
    }

    public function testCardTabsWithoutOutlineThemeModeHaveNoOutlineModifier()
    {
        $html = $this->renderComponent(
            $this->makeTabbedCardTemplate('theme="teal"')
        );

        $this->assertStringContainsString('card-tabs', $html);
        $this->assertStringNotContainsString('card-outline-tabs', $html);
    }

    public function testCardTabsKeepTheCardTools()
    {
        $html = $this->renderComponent(
            $this->makeTabbedCardTemplate('collapsible removable')
        );

        $this->assertStringContainsString('class="card-tools"', $html);
        $this->assertStringContainsString('data-lte-toggle="card-collapse"', $html);
        $this->assertStringContainsString('data-lte-toggle="card-remove"', $html);

        // The tools are floated to the right, so they must be placed before
        // the tabs navigation to sit on the same line.

        $this->assertLessThan(
            strpos($html, '<ul class="nav nav-tabs"'),
            strpos($html, 'class="card-tools"')
        );
    }

    public function testCardTabsSlotReplacesTheGeneratedNavigation()
    {
        $html = $this->renderComponent(
            '<x-adminlte-card theme="info" theme-mode="outline">'.
            '<x-slot name="tabsSlot"><ul id="my-nav" class="nav nav-tabs"></ul></x-slot>'.
            'Panes</x-adminlte-card>'
        );

        // The tabs slot turns the card into a tabbed card by itself.

        $this->assertStringContainsString('card-tabs card-outline-tabs', $html);
        $this->assertStringContainsString('class="card-header p-0 pt-1"', $html);
        $this->assertStringContainsString('<ul id="my-nav" class="nav nav-tabs"></ul>', $html);
        $this->assertStringContainsString('<div class="tab-content">', $html);
        $this->assertStringNotContainsString('role="tablist"', $html);

        $this->assertV4Markup($html);
    }

    public function testCardTabsHelpers()
    {
        $component = new Components\Widget\Card();

        $this->assertFalse($component->hasTabs());
        $this->assertTrue($component->hasTabs(true));
        $this->assertEquals([], $component->tabs);
        $this->assertFalse($component->isCardHeaderEmpty(false, false, false, true));

        // An empty or invalid set of tabs leaves a regular card.

        $component = new Components\Widget\Card(
            'T', null, null, null, null, null, null, null, null, null, null,
            null, null, []
        );

        $this->assertFalse($component->hasTabs());
        $this->assertStringNotContainsString('card-tabs', $component->makeCardClass());

        $component = new Components\Widget\Card(
            'T', null, null, null, null, null, null, null, null, null, null,
            null, null, 'not-an-array'
        );

        $this->assertFalse($component->hasTabs());
        $this->assertEquals([], $component->tabs);
    }

    public function testCardTabsHeaderClassKeepsTheExtraClasses()
    {
        $component = new Components\Widget\Card(
            null, null, null, null, 'h-cls', null, null, null, null, null,
            null, null, null, [['id' => 't', 'label' => 'T']]
        );

        $this->assertEquals(
            'card-header p-0 pt-1 h-cls',
            $component->makeCardHeaderClass()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Accessibility tests.
    |--------------------------------------------------------------------------
    */

    public function testCardToolsUseTheLocalizedAriaLabels()
    {
        $html = $this->renderComponent(
            '<x-adminlte-card title="T" collapsible removable maximizable/>'
        );

        $labels = [
            'card_maximize', 'card_collapse', 'card_remove',
        ];

        foreach ($labels as $key) {
            $label = __("adminlte::adminlte.{$key}");

            $this->assertNotEmpty($label);
            $this->assertStringContainsString('aria-label="'.e($label).'"', $html);
        }
    }

    public function testCardDisabledOverlayUsesTheLocalizedText()
    {
        $html = $this->renderComponent('<x-adminlte-card title="T" disabled/>');

        $text = __('adminlte::adminlte.card_disabled');

        $this->assertNotEmpty($text);
        $this->assertStringContainsString(
            '<span class="visually-hidden">'.e($text).'</span>', $html
        );
    }

    public function testCardCollapseToolExposesTheExpandedState()
    {
        $html = $this->renderComponent('<x-adminlte-card title="T" collapsible/>');

        $this->assertStringContainsString('aria-expanded="true"', $html);

        $html = $this->renderComponent(
            '<x-adminlte-card title="T" collapsible="collapsed"/>'
        );

        $this->assertStringContainsString('aria-expanded="false"', $html);
        $this->assertStringContainsString('collapsed-card', $html);
    }

    public function testCardIsCardCollapsedHelper()
    {
        $component = new Components\Widget\Card('T');
        $this->assertFalse($component->isCardCollapsed());

        $component = new Components\Widget\Card(
            'T', null, null, null, null, null, null, null, true
        );
        $this->assertFalse($component->isCardCollapsed());

        $component = new Components\Widget\Card(
            'T', null, null, null, null, null, null, null, 'collapsed'
        );
        $this->assertTrue($component->isCardCollapsed());
    }

    public function testCardDisabledMarksTheContentRegionsAsInert()
    {
        // The overlay covers the whole card, so the covered regions must also
        // leave the tab order. The overlay itself stays reachable by the
        // assistive technologies, it is the element announcing the state.

        $html = $this->renderComponent(
            '<x-adminlte-card title="T" disabled collapsible>Body'.
            '<x-slot name="footerSlot">Footer</x-slot></x-adminlte-card>'
        );

        $this->assertStringContainsString('<div class="card-header" inert', $html);
        $this->assertStringContainsString('<div class="card-body" inert', $html);
        $this->assertStringContainsString('<div class="card-footer" inert', $html);

        $this->assertStringNotContainsString('card-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-body bg-opacity-75 rounded" inert', $html);
    }

    public function testCardWithoutDisabledIsNotInert()
    {
        $html = $this->renderComponent(
            '<x-adminlte-card title="T">Body'.
            '<x-slot name="footerSlot">Footer</x-slot></x-adminlte-card>'
        );

        $this->assertStringNotContainsString('inert', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Backward compatibility tests.
    |--------------------------------------------------------------------------
    */

    public function testCardKeepsTheLegacyConstructorSignature()
    {
        // The new arguments were appended, so the positional order of the
        // original ones is untouched.

        $component = new Components\Widget\Card(
            'title', 'bi bi-bell', 'info', 'outline', 'h-cls', 'b-cls',
            'f-cls', true, 'collapsed', true, true
        );

        $this->assertEquals('title', $component->title);
        $this->assertEquals('bi bi-bell', $component->icon);
        $this->assertEquals('info', $component->theme);
        $this->assertEquals('outline', $component->themeMode);
        $this->assertEquals('card-header h-cls', $component->makeCardHeaderClass());
        $this->assertEquals('card-body b-cls', $component->makeCardBodyClass());
        $this->assertEquals('card-footer f-cls', $component->makeCardFooterClass());
        $this->assertEquals('card-title', $component->makeCardTitleClass());
        $this->assertTrue($component->disabled);
        $this->assertEquals('collapsed', $component->collapsible);
        $this->assertTrue($component->removable);
        $this->assertTrue($component->maximizable);

        $cClass = $component->makeCardClass();
        $this->assertStringContainsString('card-info', $cClass);
        $this->assertStringContainsString('card-outline', $cClass);
        $this->assertStringContainsString('collapsed-card', $cClass);
        $this->assertStringNotContainsString('card-tabs', $cClass);
    }

    public function testCardKeepsTheLegacyIsCardHeaderEmptySignature()
    {
        $component = new Components\Widget\Card();

        $this->assertTrue($component->isCardHeaderEmpty());
        $this->assertFalse($component->isCardHeaderEmpty(true));

        $component = new Components\Widget\Card('T');
        $this->assertFalse($component->isCardHeaderEmpty());

        $component = new Components\Widget\Card(null, 'bi bi-bell');
        $this->assertFalse($component->isCardHeaderEmpty());
    }

    public function testCardKeepsTheLegacyMarkupWithoutTheNewOptions()
    {
        $html = $this->renderComponent(
            '<x-adminlte-card title="Title" icon="bi bi-bell" theme="success"
                theme-mode="outline" header-class="h-cls" body-class="b-cls"
                footer-class="f-cls" collapsible removable maximizable>'.
            'The body'.
            '<x-slot name="toolsSlot"><span id="tool">T</span></x-slot>'.
            '<x-slot name="footerSlot">The footer</x-slot>'.
            '</x-adminlte-card>'
        );

        $this->assertStringContainsString('card mb-4 card-success card-outline', $html);
        $this->assertStringContainsString('class="card-header h-cls"', $html);
        $this->assertStringContainsString('<h3 class="card-title">', $html);
        $this->assertStringContainsString('<i class="bi bi-bell me-1"', $html);
        $this->assertStringContainsString('class="card-body b-cls"', $html);
        $this->assertStringContainsString('class="card-footer f-cls"', $html);
        $this->assertStringContainsString('<span id="tool">T</span>', $html);
        $this->assertStringContainsString('data-lte-toggle="card-maximize"', $html);
        $this->assertStringContainsString('data-lte-toggle="card-collapse"', $html);
        $this->assertStringContainsString('data-lte-toggle="card-remove"', $html);
        $this->assertStringNotContainsString('tab-content', $html);
        $this->assertStringNotContainsString('inert', $html);

        $this->assertV4Markup($html);
        $this->assertFreeOfJquery($html);
    }

    public function testCardWithEveryNewOptionRenders()
    {
        $html = $this->renderComponent(
            '<x-adminlte-card theme="indigo" theme-mode="outline"
                header-class="h-cls" body-class="b-cls" footer-class="f-cls"
                title-class="t-cls" title-tag="h5" collapsible removable
                maximizable :tabs="[
                    [\'id\' => \'t1\', \'label\' => \'One\', \'icon\' => \'bi bi-bell\'],
                    [\'id\' => \'t2\', \'label\' => \'Two\', \'active\' => true],
                ]">'.
            '<div class="tab-pane fade" id="t1" role="tabpanel" tabindex="0">One</div>'.
            '<div class="tab-pane fade show active" id="t2" role="tabpanel" tabindex="0">Two</div>'.
            '<x-slot name="footerSlot">The footer</x-slot>'.
            '</x-adminlte-card>'
        );

        $this->assertNotEmpty(trim($html));
        $this->assertStringContainsString('card-outline-tabs', $html);
        $this->assertStringContainsString('class="card-header p-0 pt-1 h-cls"', $html);
        $this->assertStringContainsString('class="card-body b-cls"', $html);
        $this->assertStringContainsString('class="card-footer f-cls"', $html);

        $this->assertV4Markup($html);
        $this->assertFreeOfJquery($html);
    }
}
