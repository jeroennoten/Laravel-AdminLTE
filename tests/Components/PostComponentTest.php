<?php

require_once __DIR__.'/ComponentTestHelpers.php';

use Illuminate\Support\Facades\Blade;
use JeroenNoten\LaravelAdminLte\View\Components;

class PostComponentTest extends TestCase
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

        // Register the components under the package prefix, the same way the
        // service provider does it for the rest of the widgets. Note the post
        // renders the user block component for its header.

        Blade::component(Components\Widget\Post::class, 'adminlte-post');

        Blade::component(
            Components\Widget\UserBlock::class, 'adminlte-user-block'
        );
    }

    public function testComponentRendersTheRightView()
    {
        $component = new Components\Widget\Post();

        $this->assertEquals(
            'adminlte::components.widget.post', $component->render()->getName()
        );
    }

    public function testComponentWithoutAuthor()
    {
        $component = new Components\Widget\Post();

        $this->assertFalse($component->hasAuthor());

        $html = $this->renderComponent('<x-adminlte-post>Content</x-adminlte-post>');

        $this->assertStringContainsString('class="post"', $html);
        $this->assertStringContainsString('Content', $html);
        $this->assertStringNotContainsString('user-block', $html);
        $this->assertV4Markup($html);
    }

    public function testComponentDetectsTheAuthorFromAnyOfItsParts()
    {
        $this->assertTrue((new Components\Widget\Post('Name'))->hasAuthor());
        $this->assertTrue((new Components\Widget\Post(null, '/i.png'))->hasAuthor());

        $this->assertTrue(
            (new Components\Widget\Post(null, null, 'Now'))->hasAuthor()
        );

        // An url alone does not make a user block, there would be nothing to
        // wrap inside the link.

        $this->assertFalse(
            (new Components\Widget\Post(null, null, null, '/u'))->hasAuthor()
        );
    }

    public function testComponentOnlyAcceptsTheSupportedSizes()
    {
        $this->assertEquals(
            'sm', (new Components\Widget\Post(null, null, null, null, 'sm'))->size
        );

        $this->assertNull(
            (new Components\Widget\Post(null, null, null, null, 'lg'))->size
        );
    }

    public function testComponentDecodesTheHtmlEntities()
    {
        $component = new Components\Widget\Post('&lt;b&gt;Jo&lt;/b&gt;', null, '&amp;now');

        $this->assertEquals('<b>Jo</b>', $component->name);
        $this->assertEquals('&now', $component->description);
    }

    public function testComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $name, $img, $description, $url, $size.

        $html = $this->renderComponent(
            '<x-adminlte-post id="post-1" class="mb-3" name="Jonathan Burke Jr."
                img="/avatar.png" description="Shared publicly - 7:30 PM today"
                url="/users/1" size="sm">
                <p>Lorem ipsum.</p>
            </x-adminlte-post>'
        );

        $this->assertStringContainsString('class="post mb-3"', $html);
        $this->assertStringContainsString('id="post-1"', $html);
        $this->assertStringContainsString('class="user-block user-block-sm"', $html);
        $this->assertStringContainsString('src="/avatar.png"', $html);
        $this->assertStringContainsString('<a href="/users/1">Jonathan Burke Jr.</a>', $html);

        $this->assertStringContainsString(
            '<span class="description">Shared publicly - 7:30 PM today</span>', $html
        );

        $this->assertStringContainsString('<p>Lorem ipsum.</p>', $html);
        $this->assertV4Markup($html);
        $this->assertFreeOfJquery($html);
    }

    public function testComponentRendersTheFooterSlot()
    {
        // The reference feed entry (dist/widgets/social.html) closes the post
        // with a margin free paragraph holding the action links.

        $html = $this->renderComponent(
            '<x-adminlte-post name="Sarah Ross">
                Body
                <x-slot name="footerSlot">
                    <a href="#" class="link-secondary">Share</a>
                </x-slot>
            </x-adminlte-post>'
        );

        $this->assertStringContainsString('<p class="mb-0">', $html);
        $this->assertStringContainsString('class="link-secondary"', $html);
        $this->assertV4Markup($html);
    }

    public function testComponentWithoutFooterSlotDoesNotRenderAParagraph()
    {
        $html = $this->renderComponent('<x-adminlte-post>Body</x-adminlte-post>');

        $this->assertStringNotContainsString('<p class="mb-0">', $html);
    }

    public function testComponentRendersWithoutAnyAttribute()
    {
        $html = $this->renderComponent('<x-adminlte-post/>');

        $this->assertNotEmpty(trim($html));
        $this->assertStringContainsString('class="post"', $html);
        $this->assertV4Markup($html);
    }
}
