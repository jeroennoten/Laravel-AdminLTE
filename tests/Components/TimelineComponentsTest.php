<?php

require_once __DIR__.'/ComponentTestHelpers.php';

use Illuminate\Support\Facades\Blade;
use JeroenNoten\LaravelAdminLte\View\Components;

class TimelineComponentsTest extends TestCase
{
    use ComponentTestHelpers;

    /**
     * Register the timeline blade components with the same prefix used by the
     * package service provider, so the templates of this test case can be
     * rendered by their public tag name.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        foreach ($this->getComponentAliases() as $alias => $component) {
            Blade::component($component, $alias, 'adminlte');
        }
    }

    /**
     * Return array with the blade alias of every timeline component.
     *
     * @return array
     */
    protected function getComponentAliases()
    {
        return [
            'timeline' => Components\Widget\Timeline::class,
            'timeline-item' => Components\Widget\TimelineItem::class,
            'timeline-label' => Components\Widget\TimelineLabel::class,
        ];
    }

    /**
     * Return array with the available blade components.
     *
     * @return array
     */
    protected function getComponents()
    {
        $base = 'adminlte::components.widget';

        return [
            "{$base}.timeline" => new Components\Widget\Timeline(),
            "{$base}.timeline-item" => new Components\Widget\TimelineItem(),
            "{$base}.timeline-label" => new Components\Widget\TimelineLabel(),
        ];
    }

    /**
     * Return array with a template exercising every attribute of each one of
     * the available timeline components.
     *
     * @return array
     */
    protected function getFullyAttributedTemplates()
    {
        return [
            'timeline' => '<x-adminlte-timeline inverse
                end-icon="bi bi-clock-fill" end-icon-theme="secondary">
                Content</x-adminlte-timeline>',
            'timeline-item' => '<x-adminlte-timeline-item icon="bi bi-envelope"
                icon-theme="primary" time="12:05" time-icon="bi bi-clock-fill"
                header="Header" url="/u" url-target="header" no-border>Body
                <x-slot name="footerSlot">Footer</x-slot>
                </x-adminlte-timeline-item>',
            'timeline-label' => '<x-adminlte-timeline-label label="10 Feb. 2023"
                theme="danger"/>',
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
        foreach ($this->getFullyAttributedTemplates() as $name => $template) {
            $html = $this->renderComponent($template);

            $this->assertNotEmpty(trim($html), "The {$name} component is empty.");
            $this->assertV4Markup($html);
            $this->assertFreeOfJquery($html);

            // The timeline widgets are fully jQuery free on AdminLTE v4.

            $this->assertFreeOfJquery($this->renderPushedAssets());
            $this->assertV4Markup($this->renderPushedAssets());
        }
    }

    public function testAllComponentsRenderWithoutAnyAttribute()
    {
        $templates = [
            '<x-adminlte-timeline/>',
            '<x-adminlte-timeline-item/>',
            '<x-adminlte-timeline-label/>',
        ];

        foreach ($templates as $template) {
            $html = $this->renderComponent($template);

            $this->assertNotEmpty(trim($html));
            $this->assertV4Markup($html);
        }
    }

    public function testAllComponentsUseOnlyClassesOfTheV4Stylesheet()
    {
        // The AdminLTE v3 timeline modifiers that were dropped by the v4
        // stylesheet must never show up on the generated markup.

        foreach ($this->getFullyAttributedTemplates() as $template) {
            $html = $this->renderComponent($template);

            $this->assertStringNotContainsString('no-border', $html);
            $this->assertStringNotContainsString('bg-gradient', $html);
            $this->assertStringNotContainsString('timeline-inverse-', $html);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Timeline component tests.
    |--------------------------------------------------------------------------
    */

    public function testTimelineComponent()
    {
        $component = new Components\Widget\Timeline();

        $this->assertEquals('timeline', $component->makeTimelineClass());
    }

    public function testTimelineComponentWithInverseStyle()
    {
        $component = new Components\Widget\Timeline(true);

        $tClass = $component->makeTimelineClass();

        $this->assertStringContainsString('timeline', $tClass);
        $this->assertStringContainsString('timeline-inverse', $tClass);
    }

    public function testTimelineComponentEndIconClass()
    {
        // Test all the constructor arguments at once:
        // $inverse, $endIcon, $endIconTheme.

        $component = new Components\Widget\Timeline(
            null, 'bi bi-clock-fill', 'secondary'
        );

        $iClass = $component->makeEndIconClass();

        $this->assertStringContainsString('timeline-icon', $iClass);
        $this->assertStringContainsString('bi bi-clock-fill', $iClass);
        $this->assertStringContainsString('text-bg-secondary', $iClass);

        // Without theme and icon only the marker class is available.

        $component = new Components\Widget\Timeline();
        $this->assertEquals('timeline-icon', $component->makeEndIconClass());
    }

    public function testTimelineComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $inverse, $endIcon, $endIconTheme.

        $html = $this->renderComponent(
            '<x-adminlte-timeline inverse end-icon="bi bi-clock-fill"
                end-icon-theme="secondary" id="tl" class="mb-0">'.
            'The entries'.
            '</x-adminlte-timeline>'
        );

        $this->assertStringContainsString('timeline timeline-inverse', $html);
        $this->assertStringContainsString('id="tl"', $html);
        $this->assertStringContainsString('mb-0', $html);
        $this->assertStringContainsString('The entries', $html);

        // The closing entry is a plain div holding the marker only, so the
        // vertical line of the timeline gets a proper end.

        $this->assertStringContainsString(
            '<i class="timeline-icon bi bi-clock-fill text-bg-secondary" aria-hidden="true">',
            $html
        );

        $this->assertV4Markup($html);
    }

    public function testTimelineComponentOmitsTheClosingEntryByDefault()
    {
        $html = $this->renderComponent('<x-adminlte-timeline>Items</x-adminlte-timeline>');

        $this->assertStringContainsString('class="timeline"', $html);
        $this->assertStringNotContainsString('timeline-inverse', $html);
        $this->assertStringNotContainsString('timeline-icon', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Timeline label component tests.
    |--------------------------------------------------------------------------
    */

    public function testTimelineLabelComponent()
    {
        $component = new Components\Widget\TimelineLabel('10 Feb. 2023', 'danger');

        $this->assertEquals('10 Feb. 2023', $component->label);
        $this->assertEquals('text-bg-danger', $component->makeLabelClass());

        // Without a theme the badge keeps the stylesheet default background.

        $component = new Components\Widget\TimelineLabel('10 Feb. 2023');
        $this->assertEquals('', $component->makeLabelClass());
    }

    public function testTimelineLabelComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $label, $theme.

        $html = $this->renderComponent(
            '<x-adminlte-timeline-label label="10 Feb. 2023" theme="danger"
                id="lbl" class="mt-3"/>'
        );

        $this->assertStringContainsString('time-label', $html);
        $this->assertStringContainsString('id="lbl"', $html);
        $this->assertStringContainsString('mt-3', $html);
        $this->assertStringContainsString(
            '<span class="text-bg-danger">10 Feb. 2023</span>',
            $html
        );

        $this->assertV4Markup($html);
    }

    public function testTimelineLabelComponentFallsBackToTheDefaultSlot()
    {
        $html = $this->renderComponent(
            '<x-adminlte-timeline-label theme="success">'.
            '<b>3 Jan. 2023</b>'.
            '</x-adminlte-timeline-label>'
        );

        $this->assertStringContainsString(
            '<span class="text-bg-success"><b>3 Jan. 2023</b></span>',
            $html
        );

        // The label attribute always takes precedence over the slot.

        $html = $this->renderComponent(
            '<x-adminlte-timeline-label label="Winner">Loser</x-adminlte-timeline-label>'
        );

        $this->assertStringContainsString('Winner', $html);
        $this->assertStringNotContainsString('Loser', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Timeline item component tests.
    |--------------------------------------------------------------------------
    */

    public function testTimelineItemComponent()
    {
        $component = new Components\Widget\TimelineItem(
            'bi bi-envelope', 'primary'
        );

        $iClass = $component->makeIconClass();

        $this->assertStringContainsString('timeline-icon', $iClass);
        $this->assertStringContainsString('bi bi-envelope', $iClass);
        $this->assertStringContainsString('text-bg-primary', $iClass);

        $this->assertEquals('timeline-header', $component->makeHeaderClass());

        // Without icon and theme the marker is still rendered, it is the dot
        // attached to the vertical line of the timeline.

        $component = new Components\Widget\TimelineItem();
        $this->assertEquals('timeline-icon', $component->makeIconClass());
    }

    public function testTimelineItemComponentDefaults()
    {
        $component = new Components\Widget\TimelineItem();

        // The reference markup always prefixes the time with a clock icon and
        // attaches the url to the header.

        $this->assertEquals('bi bi-clock-fill', $component->timeIcon);
        $this->assertEquals('header', $component->urlTarget);
    }

    public function testTimelineItemComponentHeaderIsOptional()
    {
        $component = new Components\Widget\TimelineItem();
        $this->assertTrue($component->isHeaderEmpty());
        $this->assertFalse($component->isHeaderEmpty(true));

        $component = new Components\Widget\TimelineItem(
            null, null, null, null, 'A header'
        );

        $this->assertFalse($component->isHeaderEmpty());
    }

    public function testTimelineItemComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $icon, $iconTheme, $time, $timeIcon, $header, $url, $urlTarget,
        // $noBorder.

        $html = $this->renderComponent(
            '<x-adminlte-timeline-item icon="bi bi-envelope" icon-theme="primary"
                time="12:05" time-icon="bi bi-clock-fill" header="Support Team"
                url="/mail" url-target="header" no-border id="entry"
                class="mb-4">'.
            'The message body'.
            '<x-slot name="footerSlot"><a class="btn btn-primary btn-sm">Read</a></x-slot>'.
            '</x-adminlte-timeline-item>'
        );

        // The outer element is the plain div that the '.timeline > div'
        // selector styles, so it carries the forwarded attributes.

        $this->assertStringContainsString('id="entry"', $html);
        $this->assertStringContainsString('class="mb-4"', $html);

        $this->assertStringContainsString(
            '<i class="timeline-icon bi bi-envelope text-bg-primary" aria-hidden="true">',
            $html
        );

        $this->assertStringContainsString('<div class="timeline-item">', $html);
        $this->assertStringContainsString('<span class="time">', $html);
        $this->assertStringContainsString('<i class="bi bi-clock-fill" aria-hidden="true">', $html);
        $this->assertStringContainsString('12:05', $html);
        $this->assertStringContainsString('timeline-header border-bottom-0', $html);
        $this->assertStringContainsString('href="/mail">Support Team</a>', $html);
        $this->assertStringContainsString('<div class="timeline-body">', $html);
        $this->assertStringContainsString('The message body', $html);
        $this->assertStringContainsString('<div class="timeline-footer">', $html);
        $this->assertStringContainsString('btn btn-primary btn-sm', $html);

        $this->assertV4Markup($html);
    }

    public function testTimelineItemComponentRendersOnlyTheAvailableSections()
    {
        $html = $this->renderComponent('<x-adminlte-timeline-item/>');

        $this->assertStringContainsString('class="timeline-item"', $html);
        $this->assertStringNotContainsString('class="time"', $html);
        $this->assertStringNotContainsString('timeline-header', $html);
        $this->assertStringNotContainsString('timeline-body', $html);
        $this->assertStringNotContainsString('timeline-footer', $html);

        // The header shows up with the attribute or with the slot.

        $this->assertStringContainsString(
            'timeline-header',
            $this->renderComponent('<x-adminlte-timeline-item header="H"/>')
        );

        $this->assertStringContainsString(
            'timeline-header',
            $this->renderComponent(
                '<x-adminlte-timeline-item>'.
                '<x-slot name="headerSlot">H</x-slot>'.
                '</x-adminlte-timeline-item>'
            )
        );

        // The body only shows up with a non empty default slot.

        $this->assertStringContainsString(
            'timeline-body',
            $this->renderComponent('<x-adminlte-timeline-item>B</x-adminlte-timeline-item>')
        );
    }

    public function testTimelineItemComponentHeaderSlotWinsOverTheAttribute()
    {
        $html = $this->renderComponent(
            '<x-adminlte-timeline-item header="Plain" url="/u">'.
            '<x-slot name="headerSlot"><a href="/x">Sarah</a> added you</x-slot>'.
            '</x-adminlte-timeline-item>'
        );

        $this->assertStringContainsString('<a href="/x">Sarah</a> added you', $html);
        $this->assertStringNotContainsString('Plain', $html);
        $this->assertStringNotContainsString('href="/u"', $html);
    }

    public function testTimelineItemComponentUrlTargets()
    {
        // The url is attached to the header by default.

        $html = $this->renderComponent(
            '<x-adminlte-timeline-item header="Support" time="12:05" url="/u"/>'
        );

        $this->assertStringContainsString('href="/u">Support</a>', $html);
        $this->assertEquals(1, substr_count($html, '<a '));

        // The time may be used as the link target instead.

        $html = $this->renderComponent(
            '<x-adminlte-timeline-item header="Support" time="12:05" url="/u"
                url-target="time"/>'
        );

        $this->assertStringContainsString('href="/u">12:05</a>', $html);
        $this->assertEquals(1, substr_count($html, '<a '));

        // Without an url no link is rendered at all.

        $html = $this->renderComponent(
            '<x-adminlte-timeline-item header="Support" time="12:05"/>'
        );

        $this->assertStringNotContainsString('<a ', $html);
    }

    public function testTimelineItemComponentTimeIcon()
    {
        // The clock icon of the reference markup is the default one.

        $html = $this->renderComponent('<x-adminlte-timeline-item time="12:05"/>');

        $this->assertStringContainsString('bi bi-clock-fill', $html);

        // A custom icon replaces the default one.

        $html = $this->renderComponent(
            '<x-adminlte-timeline-item time="12:05" time-icon="bi bi-hourglass"/>'
        );

        $this->assertStringContainsString('bi bi-hourglass', $html);
        $this->assertStringNotContainsString('bi bi-clock-fill', $html);

        // An empty icon renders the time alone.

        $html = $this->renderComponent(
            '<x-adminlte-timeline-item time="12:05" time-icon=""/>'
        );

        $this->assertStringContainsString('12:05', $html);
        $this->assertStringNotContainsString('bi bi-', $html);
    }

    public function testTimelineItemComponentTimeCarriesAScreenReaderLabel()
    {
        $html = $this->renderComponent('<x-adminlte-timeline-item time="12:05"/>');

        // The clock icon is decorative and the bare time gets a translated
        // screen reader label, so it is not read out of context.

        $this->assertStringContainsString('aria-hidden="true"', $html);
        $this->assertStringContainsString(
            '<span class="visually-hidden">Time</span>',
            $html
        );

        $this->assertStringNotContainsString('sr-only', $html);
    }

    public function testTimelineItemComponentDropsTheLegacyNoBorderModifier()
    {
        // On AdminLTE v4 the '.no-border' modifier of the timeline header does
        // not exist anymore, the separator is removed with a Bootstrap 5
        // utility instead.

        $component = new Components\Widget\TimelineItem(
            null, null, null, null, null, null, null, true
        );

        $hClass = $component->makeHeaderClass();

        $this->assertStringContainsString('timeline-header', $hClass);
        $this->assertStringContainsString('border-bottom-0', $hClass);
        $this->assertStringNotContainsString('no-border', $hClass);

        $html = $this->renderComponent(
            '<x-adminlte-timeline-item header="H" no-border/>'
        );

        $this->assertStringNotContainsString('no-border', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Theme resolution tests.
    |--------------------------------------------------------------------------
    */

    public function testTimelineComponentsResolveTheV3ColorAliases()
    {
        $aliases = [
            'lightblue' => 'sky',
            'maroon' => 'pink',
            'purple' => 'violet',
            'lime' => 'olive',
            'blue' => 'primary',
            'red' => 'danger',
            'green' => 'success',
            'yellow' => 'warning',
            'cyan' => 'info',
            'gray' => 'secondary',
            'gray-dark' => 'dark',
        ];

        foreach ($aliases as $v3 => $v4) {
            $timeline = new Components\Widget\Timeline(null, 'bi bi-clock', $v3);
            $item = new Components\Widget\TimelineItem('bi bi-bell', $v3);
            $label = new Components\Widget\TimelineLabel('L', $v3);

            $this->assertStringContainsString(
                "text-bg-{$v4}", $timeline->makeEndIconClass()
            );

            $this->assertStringContainsString(
                "text-bg-{$v4}", $item->makeIconClass()
            );

            $this->assertEquals("text-bg-{$v4}", $label->makeLabelClass());
        }
    }

    public function testTimelineComponentsKeepTheV3ColorNamesWithTheAliasStylesheet()
    {
        config(['adminlte.assets.extended_colors_v3_aliases' => true]);

        $item = new Components\Widget\TimelineItem('bi bi-bell', 'maroon');
        $label = new Components\Widget\TimelineLabel('L', 'maroon');

        $this->assertStringContainsString('text-bg-maroon', $item->makeIconClass());
        $this->assertEquals('text-bg-maroon', $label->makeLabelClass());
    }

    public function testTimelineComponentsSupportTheExtendedPalette()
    {
        foreach (['navy', 'teal', 'olive', 'indigo'] as $theme) {
            $html = $this->renderComponent(
                "<x-adminlte-timeline end-icon=\"bi bi-clock\" end-icon-theme=\"{$theme}\">".
                "<x-adminlte-timeline-label label=\"L\" theme=\"{$theme}\"/>".
                "<x-adminlte-timeline-item icon=\"bi bi-bell\" icon-theme=\"{$theme}\"/>".
                '</x-adminlte-timeline>'
            );

            $this->assertEquals(3, substr_count($html, "text-bg-{$theme}"));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Escaping and attribute forwarding tests.
    |--------------------------------------------------------------------------
    */

    public function testTimelineComponentsEscapeTheirTextAttributes()
    {
        $html = $this->renderComponent(
            '<x-adminlte-timeline-item header="<script>x</script>"
                time="<b>now</b>"/>'
        );

        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringNotContainsString('<b>now</b>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringContainsString('&lt;b&gt;now&lt;/b&gt;', $html);

        $html = $this->renderComponent(
            '<x-adminlte-timeline-label label="<i>10 Feb.</i>"/>'
        );

        $this->assertStringNotContainsString('<i>10 Feb.</i>', $html);
        $this->assertStringContainsString('&lt;i&gt;10 Feb.&lt;/i&gt;', $html);
    }

    public function testTimelineComponentsDecodeTheHtmlEntitiesOfTheirTextAttributes()
    {
        // The text attributes go through the html entity decoder, so a value
        // coming from an escaped source is not double encoded.

        $component = new Components\Widget\TimelineItem(
            null, null, '12:05 &amp; later', null, 'Tom &amp; Jerry'
        );

        $this->assertEquals('Tom & Jerry', $component->header);
        $this->assertEquals('12:05 & later', $component->time);

        $component = new Components\Widget\TimelineLabel('Feb &amp; Mar');
        $this->assertEquals('Feb & Mar', $component->label);

        $html = $this->renderComponent(
            '<x-adminlte-timeline-label label="Feb &amp; Mar"/>'
        );

        $this->assertStringContainsString('Feb &amp; Mar', $html);
        $this->assertStringNotContainsString('&amp;amp;', $html);
    }

    public function testTimelineComponentsForwardTheExtraAttributes()
    {
        $html = $this->renderComponent(
            '<x-adminlte-timeline id="tl" class="shadow-sm" data-foo="bar">'.
            '<x-adminlte-timeline-label id="lbl" class="mt-2" label="L"/>'.
            '<x-adminlte-timeline-item id="it" class="mb-2" title="A tooltip"/>'.
            '</x-adminlte-timeline>'
        );

        $this->assertStringContainsString('class="timeline shadow-sm"', $html);
        $this->assertStringContainsString('id="tl"', $html);
        $this->assertStringContainsString('data-foo="bar"', $html);

        $this->assertStringContainsString('class="time-label mt-2"', $html);
        $this->assertStringContainsString('id="lbl"', $html);

        $this->assertStringContainsString('id="it"', $html);
        $this->assertStringContainsString('class="mb-2"', $html);
        $this->assertStringContainsString('title="A tooltip"', $html);

        $this->assertV4Markup($html);
    }

    /*
    |--------------------------------------------------------------------------
    | Composition test.
    |--------------------------------------------------------------------------
    */

    public function testTimelineComponentsBuildTheReferenceMarkup()
    {
        $html = $this->renderComponent(
            '<x-adminlte-timeline end-icon="bi bi-clock-fill" end-icon-theme="secondary">'.
            '<x-adminlte-timeline-label label="10 Feb. 2023" theme="danger"/>'.
            '<x-adminlte-timeline-item icon="bi bi-envelope" icon-theme="primary"'.
            ' time="12:05" header="Support Team" url="#">The email body'.
            '<x-slot name="footerSlot">Read more</x-slot>'.
            '</x-adminlte-timeline-item>'.
            '<x-adminlte-timeline-item icon="bi bi-person" icon-theme="success"'.
            ' time="5 mins ago" header="Sarah Young" no-border/>'.
            '</x-adminlte-timeline>'
        );

        // The order of the elements must follow the AdminLTE reference markup.

        $positions = [
            'class="timeline"',
            'class="time-label"',
            'text-bg-danger',
            'timeline-icon bi bi-envelope text-bg-primary',
            'class="timeline-item"',
            'class="time"',
            'class="timeline-header"',
            'class="timeline-body"',
            'class="timeline-footer"',
            'timeline-icon bi bi-person text-bg-success',
            'timeline-header border-bottom-0',
            'timeline-icon bi bi-clock-fill text-bg-secondary',
        ];

        $offset = 0;

        foreach ($positions as $needle) {
            $found = strpos($html, $needle, $offset);

            $this->assertNotFalse($found, "Missing or misplaced '{$needle}'.");

            $offset = $found;
        }

        $this->assertV4Markup($html);
        $this->assertFreeOfJquery($html);
    }
}
