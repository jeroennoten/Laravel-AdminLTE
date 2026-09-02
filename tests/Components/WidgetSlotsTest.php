<?php

use JeroenNoten\LaravelAdminLte\View\Components;

class WidgetSlotsTest extends TestCase
{
    use ComponentTestHelpers;

    public function testInfoBoxTitleAndTextSlotsAcceptMarkup()
    {
        // The reference layouts put markup inside the number, which the
        // escaped attributes cannot express.

        $html = $this->renderComponent(
            '<x-adminlte-info-box>
                <x-slot name="titleSlot"><em>Bookmarks</em></x-slot>
                <x-slot name="textSlot">10 <small>%</small></x-slot>
            </x-adminlte-info-box>'
        );

        $this->assertStringContainsString('<em>Bookmarks</em>', $html);
        $this->assertStringContainsString('10 <small>%</small>', $html);
        $this->assertStringContainsString('info-box-text', $html);
        $this->assertStringContainsString('info-box-number', $html);
    }

    public function testInfoBoxSlotsWinOverTheAttributes()
    {
        $html = $this->renderComponent(
            '<x-adminlte-info-box title="A title" text="A text">
                <x-slot name="titleSlot">FROM-SLOT</x-slot>
            </x-adminlte-info-box>'
        );

        $this->assertStringContainsString('FROM-SLOT', $html);
        $this->assertStringNotContainsString('A title', $html);
        $this->assertStringContainsString('A text', $html);
    }

    public function testInfoBoxAttributesAreStillEscaped()
    {
        $html = $this->renderComponent(
            '<x-adminlte-info-box title="<script>x</script>"/>'
        );

        $this->assertStringNotContainsString('<script>x</script>', $html);
    }

    public function testInfoBoxTitleSlotKeepsTheUrlTarget()
    {
        $html = $this->renderComponent(
            '<x-adminlte-info-box url="/go" url-target="title">
                <x-slot name="titleSlot"><b>Linked</b></x-slot>
            </x-adminlte-info-box>'
        );

        $this->assertStringContainsString('info-box-url', $html);
        $this->assertStringContainsString('<b>Linked</b>', $html);
    }

    public function testSmallBoxTitleAndTextSlotsAcceptMarkup()
    {
        $html = $this->renderComponent(
            '<x-adminlte-small-box>
                <x-slot name="titleSlot">53<sup class="fs-5">%</sup></x-slot>
                <x-slot name="textSlot">Bounce <small>rate</small></x-slot>
            </x-adminlte-small-box>'
        );

        $this->assertStringContainsString('53<sup class="fs-5">%</sup>', $html);
        $this->assertStringContainsString('Bounce <small>rate</small>', $html);
    }

    public function testSmallBoxFooterSlotReplacesTheDefaultLink()
    {
        $html = $this->renderComponent(
            '<x-adminlte-small-box url="/go" url-text="More">
                <x-slot name="footerSlot"><b>Custom</b></x-slot>
            </x-adminlte-small-box>'
        );

        $this->assertStringContainsString('<b>Custom</b>', $html);
        $this->assertStringNotContainsString('More', $html);
        $this->assertStringContainsString('href="/go"', $html);
    }

    public function testSmallBoxFooterSlotWithoutAnUrlIsNotALink()
    {
        $html = $this->renderComponent(
            '<x-adminlte-small-box>
                <x-slot name="footerSlot">Plain footer</x-slot>
            </x-adminlte-small-box>'
        );

        $this->assertStringContainsString(
            '<div class="small-box-footer">Plain footer</div>',
            $html
        );
    }

    public function testSmallBoxFooterIconIsConfigurable()
    {
        // The default keeps the previous markup of the package.

        $html = $this->renderComponent(
            '<x-adminlte-small-box url="/go" url-text="More"/>'
        );

        $this->assertStringContainsString('bi bi-arrow-right-circle', $html);

        $html = $this->renderComponent(
            '<x-adminlte-small-box url="/go" url-text="More" footer-icon="bi bi-link-45deg"/>'
        );

        $this->assertStringContainsString('bi bi-link-45deg', $html);
        $this->assertStringNotContainsString('bi bi-arrow-right-circle', $html);
    }

    public function testSmallBoxFooterIconCanBeDropped()
    {
        $html = $this->renderComponent(
            '<x-adminlte-small-box url="/go" url-text="More" footer-icon=""/>'
        );

        $this->assertStringContainsString('More', $html);
        $this->assertStringNotContainsString('bi bi-arrow-right-circle', $html);
    }

    public function testSmallBoxLoadingLabelIsTranslated()
    {
        app()->setLocale('de');

        $html = $this->renderComponent('<x-adminlte-small-box loading/>');

        $this->assertStringContainsString(
            __('adminlte::adminlte.loading'),
            $html
        );
        $this->assertStringNotContainsString('>Loading<', $html);

        app()->setLocale('en');
    }

    public function testProgressLabelSlotReplacesThePercentage()
    {
        $html = $this->renderComponent(
            '<x-adminlte-progress :value="80" with-label>
                <x-slot name="labelSlot">160/200</x-slot>
            </x-adminlte-progress>'
        );

        // Note the bar style carries the percentage too, so the check is
        // done over the text content of the bar.

        $this->assertStringContainsString('160/200', $html);
        $this->assertDoesNotMatchRegularExpression('/>\s*80%\s*</', $html);
    }

    public function testProgressKeepsThePercentageWithoutTheSlot()
    {
        $html = $this->renderComponent(
            '<x-adminlte-progress :value="80" with-label/>'
        );

        $this->assertStringContainsString('80%', $html);
    }

    public function testProfileRowItemKeepsTheDefaultLayout()
    {
        // The default markup must not change, the reference layout is opt in.

        $html = $this->renderComponent(
            '<x-adminlte-profile-row-item title="Projects" text="31"/>'
        );

        $this->assertStringContainsString('p-0 col-12', $html);
        $this->assertStringContainsString('<span class="nav-link">', $html);
        $this->assertStringNotContainsString('nav-item', $html);
    }

    public function testProfileRowItemNavLayoutFollowsTheReference()
    {
        $html = $this->renderComponent(
            '<x-adminlte-profile-row-item title="Projects" text="31" layout-type="nav"/>'
        );

        $this->assertStringContainsString('nav-item', $html);
        $this->assertStringContainsString('nav-link', $html);
    }

    public function testProfileRowItemTextSlotAcceptsMarkup()
    {
        $html = $this->renderComponent(
            '<x-adminlte-profile-row-item title="Projects">
                <x-slot name="textSlot"><span class="badge">31</span></x-slot>
            </x-adminlte-profile-row-item>'
        );

        $this->assertStringContainsString('<span class="badge">31</span>', $html);
    }
}
