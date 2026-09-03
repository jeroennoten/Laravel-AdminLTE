<?php

use JeroenNoten\LaravelAdminLte\View\Components\Layout\ContentHeader;
use JeroenNoten\LaravelAdminLte\View\Components\Layout\NavbarDropdown;
use JeroenNoten\LaravelAdminLte\View\Components\Widget\Card;
use JeroenNoten\LaravelAdminLte\View\Components\Widget\DirectChat;
use JeroenNoten\LaravelAdminLte\View\Components\Widget\Toast;

/**
 * Locks the extension points of the blade components. A published (or simply
 * extended) component has to be able to replace the vocabulary its base class
 * declares, otherwise the only way to accept an extra value is to copy the
 * whole method.
 */
class ComponentOverridesTest extends TestCase
{
    public function testTheCardTitleTagsCanBeExtended()
    {
        $card = new Card(title: 'Title', titleTag: 'p');
        $this->assertEquals('h3', $card->titleTag);

        $card = new CustomCard(title: 'Title', titleTag: 'p');
        $this->assertEquals('p', $card->titleTag);
    }

    public function testTheCardDefaultTitleTagCanBeReplaced()
    {
        $card = new CustomCard(title: 'Title');

        $this->assertEquals('h2', $card->titleTag);
    }

    public function testTheDirectChatTimestampModesCanBeExtended()
    {
        $chat = new DirectChat(timestampMode: 'muted');
        $this->assertNull($chat->timestampMode);

        $chat = new CustomDirectChat(timestampMode: 'muted');
        $this->assertEquals('muted', $chat->timestampMode);
    }

    public function testTheNavbarDropdownMenuSizesCanBeExtended()
    {
        $dropdown = new NavbarDropdown(size: 'sm');
        $this->assertNull($dropdown->size);

        $dropdown = new CustomNavbarDropdown(size: 'sm');
        $this->assertEquals('sm', $dropdown->size);
    }

    public function testTheToastPositionsCanBeExtended()
    {
        $toast = new Toast(position: 'top-quarter');
        $this->assertEquals('bottom-end', $toast->position);

        $toast = new CustomToast(position: 'top-quarter');
        $this->assertEquals('top-quarter', $toast->position);
        $this->assertStringContainsString('top-25', $toast->makeContainerClass());
    }

    public function testTheContentHeaderTitleClassCanBeReplaced()
    {
        $header = new ContentHeader(title: 'Title');
        $this->assertEquals('mb-0 fs-3', $header->makeTitleClass());

        $header = new CustomContentHeader(title: 'Title');
        $this->assertEquals('mb-0 fs-1', $header->makeTitleClass());
    }

    public function testTheV3ColorAliasesCanBeExtended()
    {
        $card = new Card(theme: 'brand');
        $this->assertStringContainsString('card-brand', $card->makeCardClass());

        $card = new CustomCard(theme: 'brand');
        $this->assertStringContainsString('card-primary', $card->makeCardClass());
    }
}

class CustomCard extends Card
{
    protected const TITLE_TAGS = ['p', 'h2'];

    protected const DEFAULT_TITLE_TAG = 'h2';

    protected static $v3ColorAliases = ['brand' => 'primary'];
}

class CustomDirectChat extends DirectChat
{
    protected const TIMESTAMP_MODES = ['light', 'dark', 'muted'];
}

class CustomNavbarDropdown extends NavbarDropdown
{
    protected const MENU_SIZES = ['sm', 'lg', 'xl'];
}

class CustomToast extends Toast
{
    protected static $positions = [
        'top-quarter' => 'top-25 start-50 translate-middle-x',
    ];
}

class CustomContentHeader extends ContentHeader
{
    protected const DEFAULT_TITLE_CLASS = 'mb-0 fs-1';
}
