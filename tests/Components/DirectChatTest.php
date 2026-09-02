<?php

require_once __DIR__.'/ComponentTestHelpers.php';

use Illuminate\Support\Facades\Blade;
use JeroenNoten\LaravelAdminLte\View\Components;

class DirectChatTest extends TestCase
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

        // Register the blade aliases, so the components can be exercised
        // through the same tags the final applications use.

        foreach ($this->getComponents() as $alias => $class) {
            Blade::component($class, $alias);
        }
    }

    /**
     * Return the available direct chat components, keyed by blade alias.
     *
     * @return array
     */
    protected function getComponents()
    {
        return [
            'adminlte-direct-chat' => Components\Widget\DirectChat::class,
            'adminlte-direct-chat-msg' => Components\Widget\DirectChatMsg::class,
            'adminlte-direct-chat-contact' => Components\Widget\DirectChatContact::class,
        ];
    }

    /**
     * Return a template exercising the whole widget at once.
     *
     * @return string
     */
    protected function getFullTemplate()
    {
        return '<x-adminlte-direct-chat title="Direct Chat" icon="bi bi-chat"
            theme="info" badge="3" badge-theme="warning" height="300"
            timestamp-mode="light" contacts-light collapsible removable
            maximizable header-class="h-cls" body-class="b-cls"
            footer-class="f-cls">
                <x-adminlte-direct-chat-msg name="Alexander Pierce"
                    timestamp="23 Jan 2:00 pm" img="/img/user1.jpg">
                    Is this template really for free?
                </x-adminlte-direct-chat-msg>
                <x-adminlte-direct-chat-msg name="Sarah Bullock"
                    timestamp="23 Jan 2:05 pm" img="/img/user3.jpg" end>
                    You better believe it!
                </x-adminlte-direct-chat-msg>
                <x-slot name="contactsSlot">
                    <x-adminlte-direct-chat-contact name="Count Dracula"
                        img="/img/user1.jpg" date="2/28/2023"
                        msg="How have you been?" url="/chats/1"/>
                </x-slot>
                <x-slot name="toolsSlot"><span id="tool">T</span></x-slot>
                <x-slot name="footerSlot">The footer</x-slot>
            </x-adminlte-direct-chat>';
    }

    /*
    |--------------------------------------------------------------------------
    | General tests.
    |--------------------------------------------------------------------------
    */

    public function testAllComponentsRender()
    {
        $views = [
            Components\Widget\DirectChat::class => 'adminlte::components.widget.direct-chat',
            Components\Widget\DirectChatMsg::class => 'adminlte::components.widget.direct-chat-msg',
            Components\Widget\DirectChatContact::class => 'adminlte::components.widget.direct-chat-contact',
        ];

        foreach ($views as $class => $viewName) {
            $this->assertEquals($viewName, (new $class())->render()->getName());
        }
    }

    public function testAllComponentsRenderWithoutAnyAttribute()
    {
        $templates = [
            '<x-adminlte-direct-chat/>',
            '<x-adminlte-direct-chat-msg/>',
            '<x-adminlte-direct-chat-contact/>',
        ];

        foreach ($templates as $template) {
            $html = $this->renderComponent($template);

            $this->assertNotEmpty(trim($html));
            $this->assertV4Markup($html);
            $this->assertFreeOfJquery($html);
        }
    }

    public function testTheWholeWidgetRendersWithEveryAttribute()
    {
        $html = $this->renderComponent($this->getFullTemplate());

        $this->assertNotEmpty(trim($html));
        $this->assertV4Markup($html);
        $this->assertFreeOfJquery($html);
        $this->assertFreeOfJquery($this->renderPushedAssets());
    }

    public function testTheWidgetOnlyEmitsClassesOfTheV4Stylesheet()
    {
        // The AdminLTE v3 markup of the widget used a 'direct-chat-pane'
        // wrapper and a '.right' modifier, both dropped on v4.

        $html = $this->renderComponent($this->getFullTemplate());

        $this->assertStringNotContainsString('direct-chat-pane', $html);
        $this->assertStringNotContainsString('direct-chat-msg right', $html);
        $this->assertStringNotContainsString('contacts-list-status', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Direct chat container tests.
    |--------------------------------------------------------------------------
    */

    public function testDirectChatComponent()
    {
        $component = new Components\Widget\DirectChat('title', null, 'info');

        $cClass = $component->makeCardClass();
        $this->assertStringContainsString('card', $cClass);
        $this->assertStringContainsString('direct-chat', $cClass);
        $this->assertStringContainsString('direct-chat-info', $cClass);

        $this->assertStringContainsString(
            'card-header', $component->makeCardHeaderClass()
        );
        $this->assertStringContainsString(
            'card-body', $component->makeCardBodyClass()
        );
        $this->assertStringContainsString(
            'card-footer', $component->makeCardFooterClass()
        );
        $this->assertEquals(
            'direct-chat-contacts', $component->makeContactsClass()
        );
    }

    public function testDirectChatComponentRendersEveryAttribute()
    {
        $html = $this->renderComponent($this->getFullTemplate());

        $this->assertStringContainsString(
            'class="card direct-chat mb-4 direct-chat-info timestamp-light"',
            $html
        );

        $this->assertStringContainsString('class="card-header h-cls"', $html);
        $this->assertStringContainsString('class="card-body b-cls"', $html);
        $this->assertStringContainsString('class="card-footer f-cls"', $html);
        $this->assertStringContainsString('The footer', $html);

        $this->assertStringContainsString('class="card-title"', $html);
        $this->assertStringContainsString('<i class="bi bi-chat me-1"', $html);
        $this->assertStringContainsString('Direct Chat', $html);

        $this->assertStringContainsString('class="badge text-bg-warning"', $html);
        $this->assertStringContainsString('<span id="tool">T</span>', $html);

        $this->assertStringContainsString('direct-chat-messages', $html);
        $this->assertStringContainsString(
            'class="direct-chat-contacts direct-chat-contacts-light"', $html
        );
        $this->assertStringContainsString('<ul class="contacts-list">', $html);
    }

    public function testDirectChatComponentUsesAThemeByDefault()
    {
        // The outgoing bubble is painted through the custom properties that
        // only the '.direct-chat-{color}' variants declare, so the widget can
        // not be left without a theme.

        $component = new Components\Widget\DirectChat();

        $this->assertEquals('primary', $component->theme);
        $this->assertStringContainsString(
            'direct-chat-primary', $component->makeCardClass()
        );

        $html = $this->renderComponent('<x-adminlte-direct-chat/>');
        $this->assertStringContainsString('direct-chat-primary', $html);
    }

    public function testDirectChatComponentResolvesTheV3ThemeNames()
    {
        $html = $this->renderComponent('<x-adminlte-direct-chat theme="lightblue"/>');
        $this->assertStringContainsString('direct-chat-sky', $html);
        $this->assertStringNotContainsString('direct-chat-lightblue', $html);

        $html = $this->renderComponent('<x-adminlte-direct-chat theme="green"/>');
        $this->assertStringContainsString('direct-chat-success', $html);

        // A color of the AdminLTE v4 extended palette is kept untouched.

        $html = $this->renderComponent('<x-adminlte-direct-chat theme="teal"/>');
        $this->assertStringContainsString('direct-chat-teal', $html);
    }

    public function testDirectChatComponentKeepsTheV3ThemeNamesWithTheAliases()
    {
        config(['adminlte.assets.extended_colors_v3_aliases' => true]);

        $html = $this->renderComponent('<x-adminlte-direct-chat theme="maroon"/>');

        $this->assertStringContainsString('direct-chat-maroon', $html);
        $this->assertStringNotContainsString('direct-chat-pink', $html);
    }

    public function testDirectChatComponentKeepsASingleBottomMargin()
    {
        $html = $this->renderComponent('<x-adminlte-direct-chat/>');
        $this->assertStringContainsString('card direct-chat mb-4', $html);

        foreach (['mb-0', 'mb-5', 'my-2', 'mb-auto'] as $margin) {
            $html = $this->renderComponent(
                "<x-adminlte-direct-chat class=\"{$margin}\"/>"
            );

            $this->assertStringContainsString($margin, $html);
            $this->assertStringNotContainsString('mb-4', $html);
            $this->assertEquals(1, preg_match_all('/\bm[by]-(auto|[0-5])\b/', $html));
        }
    }

    public function testDirectChatComponentToolsUseTheV4DataHooks()
    {
        $html = $this->renderComponent(
            '<x-adminlte-direct-chat title="T" collapsible removable maximizable>
                <x-slot name="contactsSlot">C</x-slot>
            </x-adminlte-direct-chat>'
        );

        $this->assertStringContainsString('data-lte-toggle="card-maximize"', $html);
        $this->assertStringContainsString('data-lte-toggle="card-collapse"', $html);
        $this->assertStringContainsString('data-lte-toggle="card-remove"', $html);
        $this->assertStringContainsString('data-lte-toggle="chat-pane"', $html);
        $this->assertStringContainsString('bi bi-chat-text-fill', $html);
        $this->assertEquals(4, substr_count($html, 'class="btn btn-tool"'));

        $this->assertV4Markup($html);
    }

    public function testDirectChatComponentCollapsedMode()
    {
        $component = new Components\Widget\DirectChat(
            'title', null, null, null, null, null, null, null, null, null,
            null, null, 'collapsed'
        );

        $this->assertTrue($component->isCardCollapsed());
        $this->assertStringContainsString(
            'collapsed-card', $component->makeCardClass()
        );

        $html = $this->renderComponent(
            '<x-adminlte-direct-chat title="T" collapsible="collapsed"/>'
        );

        $this->assertStringContainsString('collapsed-card', $html);
        $this->assertStringContainsString('aria-expanded="false"', $html);
    }

    public function testDirectChatComponentContactsPaneNeedsItsSlot()
    {
        // Without contacts there is nothing to slide in, so neither the pane
        // nor its toggle button are rendered.

        $html = $this->renderComponent('<x-adminlte-direct-chat title="T"/>');

        $this->assertStringNotContainsString('direct-chat-contacts', $html);
        $this->assertStringNotContainsString('contacts-list', $html);
        $this->assertStringNotContainsString('data-lte-toggle="chat-pane"', $html);

        $html = $this->renderComponent(
            '<x-adminlte-direct-chat title="T">
                <x-slot name="contactsSlot">The contacts</x-slot>
            </x-adminlte-direct-chat>'
        );

        $this->assertStringContainsString('class="direct-chat-contacts"', $html);
        $this->assertStringContainsString('<ul class="contacts-list">', $html);
        $this->assertStringContainsString('The contacts', $html);
        $this->assertStringContainsString('data-lte-toggle="chat-pane"', $html);
    }

    public function testDirectChatComponentContactsPaneCanStartOpen()
    {
        // The plugin toggles the 'direct-chat-contacts-open' class, so that
        // is the one an initially open pane has to carry.

        $html = $this->renderComponent(
            '<x-adminlte-direct-chat contacts-open>
                <x-slot name="contactsSlot">C</x-slot>
            </x-adminlte-direct-chat>'
        );

        $this->assertStringContainsString('direct-chat-contacts-open', $html);
        $this->assertStringContainsString('aria-expanded="true"', $html);

        $html = $this->renderComponent(
            '<x-adminlte-direct-chat><x-slot name="contactsSlot">C</x-slot>
            </x-adminlte-direct-chat>'
        );

        $this->assertStringNotContainsString('direct-chat-contacts-open', $html);
        $this->assertStringContainsString('aria-expanded="false"', $html);
    }

    public function testDirectChatComponentHeightIsSharedByBothPanes()
    {
        // The panes are stacked one over the other, a mismatched height makes
        // the contacts pane slide in misaligned.

        $html = $this->renderComponent(
            '<x-adminlte-direct-chat height="320">
                <x-slot name="contactsSlot">C</x-slot>
            </x-adminlte-direct-chat>'
        );

        $this->assertEquals(2, substr_count($html, 'style="height: 320px;"'));

        $this->assertMatchesRegularExpression(
            '/direct-chat-messages"\s+style="height: 320px;"/', $html
        );

        $this->assertMatchesRegularExpression(
            '/direct-chat-contacts"\s+style="height: 320px;"/', $html
        );
    }

    public function testDirectChatComponentHeightNormalization()
    {
        $heights = [
            '250' => '250px',
            '250.5' => '250.5px',
            '30rem' => '30rem',
            '40vh' => '40vh',
            '100%' => '100%',
            '20em' => '20em',
            ' 250px ' => '250px',
        ];

        foreach ($heights as $given => $expected) {
            $component = new Components\Widget\DirectChat(
                null, null, null, null, null, $given
            );

            $this->assertEquals($expected, $component->height);
            $this->assertEquals(
                "height: {$expected};", $component->makePaneStyle()
            );
        }

        // A numeric attribute is accepted too.

        $component = new Components\Widget\DirectChat(
            null, null, null, null, null, 250
        );

        $this->assertEquals('250px', $component->height);
    }

    public function testDirectChatComponentRejectsAnInvalidHeight()
    {
        $heights = [
            'calc(100% - 10px)', '250px; background: red', 'auto', '"/><b>',
            null, '', [], true,
        ];

        foreach ($heights as $height) {
            $component = new Components\Widget\DirectChat(
                null, null, null, null, null, $height
            );

            $this->assertNull($component->height);
            $this->assertNull($component->makePaneStyle());
        }

        // Without a height, no style attribute reaches the panes.

        $html = $this->renderComponent(
            '<x-adminlte-direct-chat height="auto">
                <x-slot name="contactsSlot">C</x-slot>
            </x-adminlte-direct-chat>'
        );

        $this->assertStringNotContainsString('style=', $html);
    }

    public function testDirectChatComponentTimestampMode()
    {
        foreach (['light', 'dark'] as $mode) {
            $html = $this->renderComponent(
                "<x-adminlte-direct-chat timestamp-mode=\"{$mode}\"/>"
            );

            $this->assertStringContainsString("timestamp-{$mode}", $html);
        }

        // An unknown mode leaves the stylesheet default in place.

        foreach (['medium', '', 'LIGHT '] as $mode) {
            $component = new Components\Widget\DirectChat(
                null, null, null, null, null, null, $mode
            );

            $expected = trim(strtolower($mode)) === 'light' ? 'light' : null;
            $this->assertEquals($expected, $component->timestampMode);
        }

        $html = $this->renderComponent('<x-adminlte-direct-chat/>');
        $this->assertStringNotContainsString('timestamp-', $html);
    }

    public function testDirectChatComponentBadge()
    {
        // Without a badge theme, the badge follows the widget theme.

        $component = new Components\Widget\DirectChat(
            null, null, 'danger', '5'
        );

        $this->assertTrue($component->hasBadge());
        $this->assertEquals('badge text-bg-danger', $component->makeBadgeClass());

        $component = new Components\Widget\DirectChat(
            null, null, 'danger', '5', 'lightblue'
        );

        $this->assertEquals('badge text-bg-sky', $component->makeBadgeClass());

        // An absent or blank badge is not rendered at all.

        foreach ([null, '', '   '] as $badge) {
            $component = new Components\Widget\DirectChat(null, null, null, $badge);
            $this->assertFalse($component->hasBadge());
        }

        $html = $this->renderComponent('<x-adminlte-direct-chat badge="7"/>');
        $this->assertStringContainsString('class="badge text-bg-primary"', $html);
        $this->assertStringContainsString('>7</span>', $html);

        $html = $this->renderComponent('<x-adminlte-direct-chat title="T"/>');
        $this->assertStringNotContainsString('badge', $html);
    }

    public function testDirectChatComponentRendersOnlyTheAvailableSections()
    {
        // The messages pane is the reason of the widget, so it is always
        // rendered, but the header and the footer are optional.

        $html = $this->renderComponent('<x-adminlte-direct-chat/>');

        $this->assertStringNotContainsString('card-header', $html);
        $this->assertStringNotContainsString('card-footer', $html);
        $this->assertStringContainsString('card-body', $html);
        $this->assertStringContainsString('direct-chat-messages', $html);

        $component = new Components\Widget\DirectChat();
        $this->assertTrue($component->isCardHeaderEmpty());
        $this->assertFalse($component->isCardHeaderEmpty(true));
        $this->assertFalse($component->isCardHeaderEmpty(false, true));

        // The header shows up as soon as one of its items is available.

        $templates = [
            '<x-adminlte-direct-chat title="T"/>',
            '<x-adminlte-direct-chat icon="bi bi-chat"/>',
            '<x-adminlte-direct-chat badge="3"/>',
            '<x-adminlte-direct-chat collapsible/>',
            '<x-adminlte-direct-chat removable/>',
            '<x-adminlte-direct-chat maximizable/>',
            '<x-adminlte-direct-chat><x-slot name="toolsSlot">T</x-slot>
            </x-adminlte-direct-chat>',
            '<x-adminlte-direct-chat><x-slot name="contactsSlot">C</x-slot>
            </x-adminlte-direct-chat>',
        ];

        foreach ($templates as $template) {
            $this->assertStringContainsString(
                'card-header', $this->renderComponent($template)
            );
        }
    }

    public function testDirectChatComponentMessagesPaneIsAccessible()
    {
        $html = $this->renderComponent('<x-adminlte-direct-chat/>');

        $this->assertStringContainsString('role="log"', $html);
        $this->assertStringContainsString('tabindex="0"', $html);
        $this->assertStringContainsString(
            'aria-label="'.__('adminlte::adminlte.direct_chat_messages').'"',
            $html
        );
    }

    public function testDirectChatComponentUsesTheTranslatedLabels()
    {
        $html = $this->renderComponent(
            '<x-adminlte-direct-chat badge="3" collapsible removable maximizable>
                <x-slot name="contactsSlot">C</x-slot>
            </x-adminlte-direct-chat>'
        );

        $labels = [
            'direct_chat_messages', 'direct_chat_contacts',
            'direct_chat_new_messages', 'card_collapse', 'card_remove',
            'card_maximize',
        ];

        foreach ($labels as $key) {
            $this->assertStringContainsString(
                __("adminlte::adminlte.{$key}"), $html
            );
        }
    }

    public function testDirectChatComponentForwardsTheExtraAttributes()
    {
        $html = $this->renderComponent(
            '<x-adminlte-direct-chat id="chat" class="shadow-sm"
                data-foo="bar" onclick="fn()"/>'
        );

        $this->assertStringContainsString('id="chat"', $html);
        $this->assertStringContainsString('data-foo="bar"', $html);
        $this->assertStringContainsString('onclick="fn()"', $html);
        $this->assertStringContainsString('shadow-sm', $html);
        $this->assertStringContainsString('card direct-chat mb-4', $html);
    }

    public function testDirectChatComponentEscapesTheTextAttributes()
    {
        $html = $this->renderComponent(
            '<x-adminlte-direct-chat title="<b>T</b>" badge="<i>3</i>"/>'
        );

        $this->assertStringNotContainsString('<b>T</b>', $html);
        $this->assertStringNotContainsString('<i>3</i>', $html);
        $this->assertStringContainsString('&lt;b&gt;T&lt;/b&gt;', $html);

        // The HTML entities of the text attributes are decoded first.

        $html = $this->renderComponent(
            '<x-adminlte-direct-chat title="Chat &middot; Live"/>'
        );

        $this->assertStringContainsString('Chat · Live', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Direct chat message tests.
    |--------------------------------------------------------------------------
    */

    public function testDirectChatMsgComponent()
    {
        $component = new Components\Widget\DirectChatMsg('Name', '2:00 pm');

        $this->assertEquals('direct-chat-msg', $component->makeMsgClass());
        $this->assertFalse($component->isInfosEmpty());
    }

    public function testDirectChatMsgComponentRendersEveryAttribute()
    {
        $html = $this->renderComponent(
            '<x-adminlte-direct-chat-msg name="Alexander Pierce"
                timestamp="23 Jan 2:00 pm" img="/img/user1.jpg" id="m1">
                Is this template really for free?
            </x-adminlte-direct-chat-msg>'
        );

        $this->assertStringContainsString('class="direct-chat-msg"', $html);
        $this->assertStringContainsString('id="m1"', $html);
        $this->assertStringContainsString('class="direct-chat-infos clearfix"', $html);
        $this->assertStringContainsString('Alexander Pierce', $html);
        $this->assertStringContainsString('23 Jan 2:00 pm', $html);
        $this->assertStringContainsString(
            '<img class="direct-chat-img" src="/img/user1.jpg" alt="Alexander Pierce">',
            $html
        );
        $this->assertStringContainsString(
            '<div class="direct-chat-text">', $html
        );
        $this->assertStringContainsString(
            'Is this template really for free?', $html
        );

        $this->assertV4Markup($html);
    }

    public function testDirectChatMsgComponentFlipsTheFloatsOnAnEndMessage()
    {
        // The name always stands over the avatar, so the name and the
        // timestamp swap their sides between an incoming and an outgoing
        // message.

        $component = new Components\Widget\DirectChatMsg('Name', '2:00 pm');

        $this->assertEquals(
            'direct-chat-name float-start', $component->makeNameClass()
        );
        $this->assertEquals(
            'direct-chat-timestamp float-end', $component->makeTimestampClass()
        );

        $component = new Components\Widget\DirectChatMsg(
            'Name', '2:00 pm', null, true
        );

        $this->assertEquals('direct-chat-msg end', $component->makeMsgClass());
        $this->assertEquals(
            'direct-chat-name float-end', $component->makeNameClass()
        );
        $this->assertEquals(
            'direct-chat-timestamp float-start', $component->makeTimestampClass()
        );

        // The same flip on the rendered markup.

        $html = $this->renderComponent(
            '<x-adminlte-direct-chat-msg name="N" timestamp="T"/>'
        );

        $this->assertStringContainsString('class="direct-chat-msg"', $html);
        $this->assertStringContainsString('direct-chat-name float-start', $html);
        $this->assertStringContainsString('direct-chat-timestamp float-end', $html);

        $html = $this->renderComponent(
            '<x-adminlte-direct-chat-msg name="N" timestamp="T" end/>'
        );

        $this->assertStringContainsString('class="direct-chat-msg end"', $html);
        $this->assertStringContainsString('direct-chat-name float-end', $html);
        $this->assertStringContainsString('direct-chat-timestamp float-start', $html);

        $this->assertV4Markup($html);
    }

    public function testDirectChatMsgComponentRendersOnlyTheAvailableSections()
    {
        $component = new Components\Widget\DirectChatMsg();
        $this->assertTrue($component->isInfosEmpty());

        $html = $this->renderComponent(
            '<x-adminlte-direct-chat-msg>Just the text</x-adminlte-direct-chat-msg>'
        );

        $this->assertStringNotContainsString('direct-chat-infos', $html);
        $this->assertStringNotContainsString('direct-chat-img', $html);
        $this->assertStringContainsString('direct-chat-text', $html);
        $this->assertStringContainsString('Just the text', $html);

        // Each part of the informations line is independent.

        $html = $this->renderComponent('<x-adminlte-direct-chat-msg name="N"/>');
        $this->assertStringContainsString('direct-chat-name', $html);
        $this->assertStringNotContainsString('direct-chat-timestamp', $html);

        $html = $this->renderComponent('<x-adminlte-direct-chat-msg timestamp="T"/>');
        $this->assertStringNotContainsString('direct-chat-name', $html);
        $this->assertStringContainsString('direct-chat-timestamp', $html);
    }

    public function testDirectChatMsgComponentEscapesTheTextAttributes()
    {
        $html = $this->renderComponent(
            '<x-adminlte-direct-chat-msg name="<b>N</b>" timestamp="<i>T</i>"
                img="/i.png"/>'
        );

        $this->assertStringNotContainsString('<b>N</b>', $html);
        $this->assertStringNotContainsString('<i>T</i>', $html);
        $this->assertStringContainsString('&lt;b&gt;N&lt;/b&gt;', $html);

        // The avatar takes the name as its alternative text, so it has to be
        // escaped there too.

        $this->assertStringContainsString('alt="&lt;b&gt;N&lt;/b&gt;"', $html);

        $html = $this->renderComponent(
            '<x-adminlte-direct-chat-msg timestamp="23 Jan &middot; 2:00 pm"/>'
        );

        $this->assertStringContainsString('23 Jan · 2:00 pm', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Direct chat contact tests.
    |--------------------------------------------------------------------------
    */

    public function testDirectChatContactComponent()
    {
        $component = new Components\Widget\DirectChatContact('Name');

        $this->assertFalse($component->hasMsg());
        $this->assertTrue($component->hasMsg(true));

        $component = new Components\Widget\DirectChatContact(
            'Name', null, null, 'The msg'
        );

        $this->assertTrue($component->hasMsg());
    }

    public function testDirectChatContactComponentRendersEveryAttribute()
    {
        $html = $this->renderComponent(
            '<x-adminlte-direct-chat-contact name="Count Dracula"
                img="/img/user1.jpg" date="2/28/2023" msg="How have you been?"
                url="/chats/1" id="c1" class="active"/>'
        );

        $this->assertStringContainsString('<li class="active" id="c1">', $html);
        $this->assertStringContainsString('<a href="/chats/1">', $html);
        $this->assertStringContainsString(
            '<img class="contacts-list-img" src="/img/user1.jpg" alt="Count Dracula">',
            $html
        );
        $this->assertStringContainsString('class="contacts-list-info"', $html);
        $this->assertStringContainsString('class="contacts-list-name"', $html);
        $this->assertStringContainsString('Count Dracula', $html);
        $this->assertStringContainsString(
            '<small class="contacts-list-date float-end">2/28/2023</small>', $html
        );
        $this->assertStringContainsString('class="contacts-list-msg"', $html);
        $this->assertStringContainsString('How have you been?', $html);

        $this->assertV4Markup($html);
    }

    public function testDirectChatContactComponentWithoutUrl()
    {
        $html = $this->renderComponent(
            '<x-adminlte-direct-chat-contact name="Sarah Doe"/>'
        );

        $this->assertStringNotContainsString('<a href', $html);
        $this->assertStringNotContainsString('</a>', $html);
        $this->assertStringNotContainsString('contacts-list-img', $html);
        $this->assertStringNotContainsString('contacts-list-date', $html);
        $this->assertStringNotContainsString('contacts-list-msg', $html);
        $this->assertStringContainsString('Sarah Doe', $html);
    }

    public function testDirectChatContactComponentSlotWinsOverTheMsgAttribute()
    {
        $html = $this->renderComponent(
            '<x-adminlte-direct-chat-contact name="N" msg="FROM-ATTRIBUTE">
                <em>FROM-SLOT</em>
            </x-adminlte-direct-chat-contact>'
        );

        $this->assertStringContainsString('<em>FROM-SLOT</em>', $html);
        $this->assertStringNotContainsString('FROM-ATTRIBUTE', $html);
        $this->assertStringContainsString('contacts-list-msg', $html);
    }

    public function testDirectChatContactComponentEscapesTheTextAttributes()
    {
        $html = $this->renderComponent(
            '<x-adminlte-direct-chat-contact name="<b>N</b>" date="<i>D</i>"
                msg="<u>M</u>" img="/i.png"/>'
        );

        foreach (['<b>N</b>', '<i>D</i>', '<u>M</u>'] as $markup) {
            $this->assertStringNotContainsString($markup, $html);
        }

        $this->assertStringContainsString('&lt;b&gt;N&lt;/b&gt;', $html);
        $this->assertStringContainsString('alt="&lt;b&gt;N&lt;/b&gt;"', $html);

        $html = $this->renderComponent(
            '<x-adminlte-direct-chat-contact msg="I&rsquo;ll call you back"/>'
        );

        $this->assertStringContainsString('I’ll call you back', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Composition tests.
    |--------------------------------------------------------------------------
    */

    public function testTheWidgetKeepsTheReferenceStructure()
    {
        $html = $this->renderComponent($this->getFullTemplate());

        // The panes are siblings inside the card body, and the contacts list
        // is the only child of the contacts pane.

        $this->assertMatchesRegularExpression(
            '/card-body[^>]*>.*direct-chat-messages.*direct-chat-contacts.*'.
            'contacts-list.*<\/div>\s*(<!--.*-->)?\s*<div class="card-footer/s',
            $html
        );

        // Both messages live inside the messages pane, and the outgoing one
        // is the one carrying the '.end' modifier.

        $this->assertEquals(1, substr_count($html, 'class="direct-chat-msg"'));
        $this->assertEquals(1, substr_count($html, 'class="direct-chat-msg end"'));
        $this->assertEquals(2, substr_count($html, 'direct-chat-text'));
        $this->assertEquals(1, substr_count($html, 'contacts-list-info'));
    }
}
