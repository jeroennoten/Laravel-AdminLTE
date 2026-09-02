<?php

require_once __DIR__.'/ComponentTestHelpers.php';

use JeroenNoten\LaravelAdminLte\View\Components;

class WidgetStylesheetFeaturesTest extends TestCase
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
    | Card maximized state tests.
    |--------------------------------------------------------------------------
    */

    public function testCardMaximizedInitialState()
    {
        $component = new Components\Widget\Card(
            'title', null, null, null, null, null, null, null, null, null,
            'maximized'
        );

        $this->assertTrue($component->isCardMaximized());

        $this->assertStringContainsString(
            'maximized-card', $component->makeCardClass()
        );

        $html = $this->renderComponent(
            '<x-adminlte-card title="T" maximizable="maximized">B</x-adminlte-card>'
        );

        $this->assertStringContainsString(
            'class="card mb-4 maximized-card"', $html
        );

        // The tool button is still rendered, so the card can be restored.

        $this->assertStringContainsString(
            'data-lte-toggle="card-maximize"', $html
        );

        $this->assertV4Markup($html);
    }

    public function testCardMaximizableKeepsTheRestingStateByDefault()
    {
        // Backward compatibility: any other value only enables the tool
        // button, it never sets the resting state class.

        foreach (['', '1', 'true', 'maximize'] as $value) {
            $html = $this->renderComponent(
                "<x-adminlte-card title=\"T\" maximizable=\"{$value}\">B</x-adminlte-card>"
            );

            $this->assertStringNotContainsString('maximized-card', $html);
            $this->assertStringContainsString(
                'data-lte-toggle="card-maximize"', $html
            );
        }

        $html = $this->renderComponent(
            '<x-adminlte-card title="T" maximizable>B</x-adminlte-card>'
        );

        $this->assertStringContainsString('class="card mb-4"', $html);
        $this->assertStringNotContainsString('maximized-card', $html);
    }

    public function testCardMaximizedAndCollapsedAddsTheWasCollapsedFlag()
    {
        // The AdminLTE card plugin flags a card that was collapsed when it got
        // maximized, and the stylesheet uses the flag to keep the body of a
        // maximized card visible.

        $component = new Components\Widget\Card(
            'title', null, null, null, null, null, null, null, 'collapsed',
            null, 'maximized'
        );

        $cardClass = $component->makeCardClass();

        $this->assertStringContainsString('collapsed-card', $cardClass);
        $this->assertStringContainsString('maximized-card', $cardClass);
        $this->assertStringContainsString('was-collapsed', $cardClass);

        // The flag is only meaningful together with the collapsed state.

        $component = new Components\Widget\Card(
            'title', null, null, null, null, null, null, null, null, null,
            'maximized'
        );

        $this->assertStringNotContainsString(
            'was-collapsed', $component->makeCardClass()
        );
    }

    public function testCardMaximizedLocksThePageScroll()
    {
        $this->renderComponent(
            '<x-adminlte-card title="T" maximizable="maximized">B</x-adminlte-card>'
        );

        $js = $this->renderPushedAssets();

        $this->assertStringContainsString(
            "document.documentElement.classList.add('maximized-card')", $js
        );

        $this->assertFreeOfJquery($js);
    }

    public function testCardWithoutMaximizedStatePushesNoScript()
    {
        // Backward compatibility: an ordinary card pushes nothing on the
        // javascript stack.

        $this->renderComponent(
            '<x-adminlte-card title="T" maximizable collapsible removable>B</x-adminlte-card>'
        );

        $this->assertStringNotContainsString(
            'maximized-card', $this->renderPushedAssets()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Card height control utility tests.
    |--------------------------------------------------------------------------
    */

    public function testCardHeightControlUtilityReachesTheCardElement()
    {
        // The '.height-control' rule of the AdminLTE stylesheet lives on the
        // card element (it caps the body height), so the plain 'class'
        // attribute is all that is needed to opt in.

        $html = $this->renderComponent(
            '<x-adminlte-card title="T" class="height-control">B</x-adminlte-card>'
        );

        $this->assertStringContainsString(
            'class="card mb-4 height-control"', $html
        );

        $this->assertStringContainsString('class="card-body"', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Callout link tests.
    |--------------------------------------------------------------------------
    */

    public function testCalloutUrlRendersTheCalloutLink()
    {
        $component = new Components\Widget\Callout(
            'info', null, null, null, '/read', 'Read more'
        );

        $this->assertEquals('/read', $component->url);
        $this->assertEquals('Read more', $component->urlText);

        $html = $this->renderComponent(
            '<x-adminlte-callout theme="info" title="T" url="/read"'.
            ' url-text="Read more">Body</x-adminlte-callout>'
        );

        $this->assertStringContainsString(
            '<a href="/read" class="callout-link">', $html
        );

        $this->assertStringContainsString('Read more', $html);

        // The link is placed after the callout content.

        $this->assertTrue(
            strpos($html, 'Body') < strpos($html, 'callout-link')
        );

        $this->assertV4Markup($html);
    }

    public function testCalloutUrlTextDefaultsToTheUrl()
    {
        $html = $this->renderComponent(
            '<x-adminlte-callout url="/read">Body</x-adminlte-callout>'
        );

        $this->assertStringContainsString(
            '<a href="/read" class="callout-link"> /read </a>',
            $this->squish($html)
        );
    }

    public function testCalloutUrlTextDecodesHtmlEntities()
    {
        $component = new Components\Widget\Callout(
            null, null, null, null, '/read', 'Caf&eacute; &amp; Bar'
        );

        $this->assertEquals('Café & Bar', $component->urlText);
    }

    public function testCalloutLinkSlotReplacesTheLinkText()
    {
        $html = $this->renderComponent(
            '<x-adminlte-callout url="/read" url-text="Ignored">Body'.
            '<x-slot name="linkSlot"><i class="bi bi-arrow-right"></i> Go</x-slot>'.
            '</x-adminlte-callout>'
        );

        $this->assertStringContainsString('class="callout-link"', $html);
        $this->assertStringContainsString('<i class="bi bi-arrow-right"></i>', $html);
        $this->assertStringContainsString('Go', $html);
        $this->assertStringNotContainsString('Ignored', $html);
    }

    public function testCalloutWithoutUrlRendersNoLink()
    {
        // Backward compatibility: the markup of a callout without an url is
        // left untouched.

        $component = new Components\Widget\Callout('danger');

        $this->assertNull($component->url);
        $this->assertNull($component->urlText);

        $html = $this->renderComponent(
            '<x-adminlte-callout theme="danger">Body</x-adminlte-callout>'
        );

        $this->assertStringNotContainsString('callout-link', $html);
        $this->assertStringNotContainsString('<a ', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Stacked progress bar tests.
    |--------------------------------------------------------------------------
    */

    public function testProgressWithoutSegmentsKeepsTheSingleBarMarkup()
    {
        // Backward compatibility: the component still emits a single
        // '.progress' track holding the aria attributes.

        $component = new Components\Widget\Progress(40);

        $this->assertFalse($component->isStacked());
        $this->assertEquals([], $component->segments);
        $this->assertStringContainsString(
            'progress', $component->makeProgressClass()
        );

        $html = $this->renderComponent('<x-adminlte-progress :value="40"/>');

        $this->assertStringContainsString('class="progress mb-2"', $html);
        $this->assertStringNotContainsString('progress-stacked', $html);
        $this->assertStringContainsString('role="progressbar"', $html);
    }

    public function testProgressStackedRendersOneTrackPerSegment()
    {
        $html = $this->renderComponent(
            '<x-adminlte-progress :segments="[
                [\'value\' => 15, \'theme\' => \'primary\'],
                [\'value\' => 30, \'theme\' => \'success\'],
                [\'value\' => 20, \'theme\' => \'info\'],
            ]"/>'
        );

        $squished = $this->squish($html);

        $this->assertStringContainsString('class="progress-stacked mb-2"', $squished);
        $this->assertEquals(3, substr_count($squished, 'class="progress"'));
        $this->assertEquals(3, substr_count($squished, 'role="progressbar"'));

        $this->assertStringContainsString('style="width:15%"', $squished);
        $this->assertStringContainsString('style="width:30%"', $squished);
        $this->assertStringContainsString('style="width:20%"', $squished);

        $this->assertStringContainsString('text-bg-primary', $squished);
        $this->assertStringContainsString('text-bg-success', $squished);
        $this->assertStringContainsString('text-bg-info', $squished);

        // The aria attributes belong to every track, never to the stacked
        // container.

        $this->assertStringNotContainsString(
            'class="progress-stacked mb-2" role', $squished
        );

        $this->assertEquals(3, substr_count($squished, 'aria-valuemax="100"'));

        $this->assertV4Markup($html);
    }

    public function testProgressStackedSegmentsInheritTheComponentOptions()
    {
        $component = new Components\Widget\Progress(
            0, 'danger', null, true, null, true, null, [['value' => 50]]
        );

        $this->assertTrue($component->isStacked());

        $segment = $component->segments[0];

        $this->assertEquals(50, $segment['value']);
        $this->assertEquals('danger', $segment['theme']);
        $this->assertTrue($segment['striped']);
        $this->assertTrue($segment['animated']);

        $barClass = $component->makeSegmentBarClass($segment);

        $this->assertStringContainsString('text-bg-danger', $barClass);
        $this->assertStringContainsString('progress-bar-striped', $barClass);
        $this->assertStringContainsString('progress-bar-animated', $barClass);
    }

    public function testProgressStackedSegmentsOverrideTheComponentOptions()
    {
        $component = new Components\Widget\Progress(
            0, 'danger', null, true, null, true, null,
            [['value' => 50, 'theme' => 'success', 'striped' => false, 'animated' => false]]
        );

        $segment = $component->segments[0];

        $this->assertEquals('success', $segment['theme']);
        $this->assertNull($segment['striped']);
        $this->assertNull($segment['animated']);

        $barClass = $component->makeSegmentBarClass($segment);

        $this->assertStringContainsString('text-bg-success', $barClass);
        $this->assertStringNotContainsString('progress-bar-striped', $barClass);
        $this->assertStringNotContainsString('progress-bar-animated', $barClass);
    }

    public function testProgressStackedSegmentThemeSupportsTheColorAliases()
    {
        $component = new Components\Widget\Progress(
            0, 'info', null, null, null, null, null,
            [['value' => 10, 'theme' => 'lightblue'], ['value' => 10, 'theme' => '']]
        );

        $this->assertStringContainsString(
            'text-bg-sky', $component->makeSegmentBarClass($component->segments[0])
        );

        // An empty theme inherits the color of the container.

        $this->assertEquals(
            'progress-bar fw-bold',
            $component->makeSegmentBarClass($component->segments[1])
        );
    }

    public function testProgressStackedSegmentValuesAreClamped()
    {
        $component = new Components\Widget\Progress(
            0, 'info', null, null, null, null, null,
            [['value' => -30], ['value' => 250], []]
        );

        $this->assertEquals(0, $component->segments[0]['value']);
        $this->assertEquals(100, $component->segments[1]['value']);
        $this->assertEquals(0, $component->segments[2]['value']);
    }

    public function testProgressStackedAcceptsScalarSegments()
    {
        $component = new Components\Widget\Progress(
            0, 'info', null, null, null, null, null, [25, 35]
        );

        $this->assertEquals(25, $component->segments[0]['value']);
        $this->assertEquals(35, $component->segments[1]['value']);
        $this->assertEquals('info', $component->segments[1]['theme']);

        $html = $this->renderComponent(
            '<x-adminlte-progress :segments="[25, 35]"/>'
        );

        $this->assertStringContainsString('style="width:25%"', $html);
        $this->assertStringContainsString('style="width:35%"', $html);
    }

    public function testProgressStackedIgnoresAnEmptyOrInvalidSegmentSet()
    {
        // Backward compatibility: anything that is not a filled array leaves
        // the component on the single bar mode.

        foreach ([null, [], 'nope', 42] as $segments) {
            $component = new Components\Widget\Progress(
                40, 'info', null, null, null, null, null, $segments
            );

            $this->assertFalse($component->isStacked());
            $this->assertEquals('progress mb-2', $component->makeProgressClass());
        }
    }

    public function testProgressStackedRepeatsTheSizeOnEveryTrack()
    {
        // The height of a '.progress' element is not inherited from the
        // stacked container, so the size class has to be on both.

        $component = new Components\Widget\Progress(
            0, 'info', 'xs', null, null, null, null, [['value' => 40]]
        );

        $this->assertStringContainsString(
            'progress-xs', $component->makeProgressClass()
        );

        $this->assertEquals('progress progress-xs', $component->makeSegmentClass());

        // An unknown size is ignored on both.

        $component = new Components\Widget\Progress(
            0, 'info', 'huge', null, null, null, null, [['value' => 40]]
        );

        $this->assertStringNotContainsString(
            'progress-huge', $component->makeProgressClass()
        );

        $this->assertEquals('progress', $component->makeSegmentClass());
    }

    public function testProgressStackedIgnoresTheVerticalMode()
    {
        // The vertical mode is an AdminLTE modifier of a single track, the
        // Bootstrap stacked layout has no vertical counterpart.

        $component = new Components\Widget\Progress(
            0, 'info', null, null, true, null, null, [['value' => 40]]
        );

        $this->assertStringNotContainsString(
            'vertical', $component->makeProgressClass()
        );

        // Backward compatibility: a non stacked bar still goes vertical.

        $component = new Components\Widget\Progress(40, 'info', null, null, true);

        $this->assertStringContainsString(
            'vertical', $component->makeProgressClass()
        );
    }

    public function testProgressStackedLabels()
    {
        $component = new Components\Widget\Progress(
            0, 'info', null, null, null, null, null,
            [['value' => 40, 'label' => 'Used'], ['value' => 20]]
        );

        // Without the 'with-label' flag only the explicit labels show up.

        $this->assertEquals('Used', $component->makeSegmentLabel($component->segments[0]));
        $this->assertNull($component->makeSegmentLabel($component->segments[1]));
        $this->assertFalse($component->isSegmentLabelAuto($component->segments[0]));
        $this->assertFalse($component->isSegmentLabelAuto($component->segments[1]));

        $component = new Components\Widget\Progress(
            0, 'info', null, null, null, null, true,
            [['value' => 40, 'label' => 'Used'], ['value' => 20]]
        );

        $this->assertEquals('Used', $component->makeSegmentLabel($component->segments[0]));
        $this->assertEquals('20%', $component->makeSegmentLabel($component->segments[1]));

        // Only the built-in percentage label is refreshed by the javascript
        // utility class.

        $this->assertFalse($component->isSegmentLabelAuto($component->segments[0]));
        $this->assertTrue($component->isSegmentLabelAuto($component->segments[1]));

        $html = $this->renderComponent(
            '<x-adminlte-progress with-label :segments="[
                [\'value\' => 40, \'label\' => \'Used\'],
                [\'value\' => 20],
            ]"/>'
        );

        $squished = $this->squish($html);

        $this->assertStringContainsString('> Used </div>', $squished);
        $this->assertStringContainsString('data-progress-label="auto"> 20% </div>', $squished);
        $this->assertEquals(1, substr_count($squished, 'data-progress-label="auto"'));
    }

    public function testProgressStackedSegmentLabelDecodesHtmlEntities()
    {
        $component = new Components\Widget\Progress(
            0, 'info', null, null, null, null, null,
            [['value' => 40, 'label' => '160&#47;200']]
        );

        $this->assertEquals('160/200', $component->segments[0]['label']);
    }

    public function testProgressStackedAccessibleLabels()
    {
        $component = new Components\Widget\Progress(
            0, 'info', null, null, null, null, null,
            [['value' => 40, 'label' => 'Used'], ['value' => 20]]
        );

        $this->assertEquals(
            'Used', $component->makeSegmentAriaLabel($component->segments[0])
        );

        $this->assertEquals(
            __('adminlte::adminlte.progress'),
            $component->makeSegmentAriaLabel($component->segments[1])
        );
    }

    public function testProgressStackedRegistersTheJavascriptHelper()
    {
        $this->renderComponent(
            '<x-adminlte-progress id="pb" :segments="[10, 20]"/>'
        );

        $js = $this->renderPushedAssets();

        $this->assertStringContainsString('class _AdminLTE_Progress', $js);

        // The utility class reaches a given segment by index and moves the
        // percentage of a stacked bar to the track.

        $this->assertStringContainsString('getProgress(index = 0)', $js);
        $this->assertStringContainsString('getValue(index = 0)', $js);
        $this->assertStringContainsString('setValue(value, index = 0)', $js);
        $this->assertStringContainsString("classList.contains('progress-stacked')", $js);

        $this->assertFreeOfJquery($js);
    }
}
