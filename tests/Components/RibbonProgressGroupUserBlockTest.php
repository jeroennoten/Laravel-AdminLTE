<?php

require_once __DIR__.'/ComponentTestHelpers.php';

use Illuminate\Support\Facades\Blade;
use JeroenNoten\LaravelAdminLte\View\Components;

class RibbonProgressGroupUserBlockTest extends TestCase
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
        // service provider does it for the rest of the widgets.

        Blade::component(Components\Widget\Ribbon::class, 'adminlte-ribbon');

        Blade::component(
            Components\Widget\ProgressGroup::class, 'adminlte-progress-group'
        );

        Blade::component(
            Components\Widget\UserBlock::class, 'adminlte-user-block'
        );
    }

    /**
     * Return array with a template exercising every attribute of each one of
     * the components under test.
     *
     * @return array
     */
    protected function getFullyAttributedTemplates()
    {
        return [
            'ribbon' => '<x-adminlte-ribbon label="New" theme="info" size="lg"
                url="/news"/>',
            'progress-group' => '<x-adminlte-progress-group label="Cart"
                :value="160" :max="200" theme="info" size="sm"/>',
            'user-block' => '<x-adminlte-user-block name="Name" img="/i.png"
                description="Desc" url="/u" size="sm">Comment</x-adminlte-user-block>',
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
            "{$base}.ribbon" => new Components\Widget\Ribbon(),
            "{$base}.progress-group" => new Components\Widget\ProgressGroup(),
            "{$base}.user-block" => new Components\Widget\UserBlock(),
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

            $this->assertFreeOfJquery($this->renderPushedAssets());
            $this->assertV4Markup($this->renderPushedAssets());
        }
    }

    public function testAllComponentsRenderWithoutAnyAttribute()
    {
        $templates = [
            '<x-adminlte-ribbon/>',
            '<x-adminlte-progress-group/>',
            '<x-adminlte-user-block/>',
        ];

        foreach ($templates as $template) {
            $html = $this->renderComponent($template);

            $this->assertNotEmpty(trim($html));
            $this->assertV4Markup($html);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Ribbon component tests.
    |--------------------------------------------------------------------------
    */

    public function testRibbonComponentWithoutTheme()
    {
        $component = new Components\Widget\Ribbon();

        $this->assertEquals('ribbon-wrapper', $component->makeWrapperClass());
        $this->assertEquals('ribbon', $component->makeRibbonClass());
    }

    public function testRibbonComponentWithTheme()
    {
        $component = new Components\Widget\Ribbon('New', 'danger');

        $this->assertEquals('New', $component->label);

        $rClass = $component->makeRibbonClass();

        $this->assertStringContainsString('ribbon', $rClass);
        $this->assertStringContainsString('text-bg-danger', $rClass);
    }

    public function testRibbonComponentResolvesTheV3ThemeColors()
    {
        $component = new Components\Widget\Ribbon('New', 'lightblue');

        $this->assertStringContainsString(
            'text-bg-sky', $component->makeRibbonClass()
        );

        // With the v3 alias stylesheet in use, the old name is a real CSS
        // class and must be kept untouched.

        config(['adminlte.assets.extended_colors_v3_aliases' => true]);

        $this->assertStringContainsString(
            'text-bg-lightblue', $component->makeRibbonClass()
        );
    }

    public function testRibbonComponentSizes()
    {
        $component = new Components\Widget\Ribbon(null, null, 'lg');

        $this->assertStringContainsString(
            'ribbon-lg', $component->makeWrapperClass()
        );

        $component = new Components\Widget\Ribbon(null, null, 'xl');

        $this->assertStringContainsString(
            'ribbon-xl', $component->makeWrapperClass()
        );

        // An unsupported size is ignored.

        $component = new Components\Widget\Ribbon(null, null, 'xxl');

        $this->assertEquals('ribbon-wrapper', $component->makeWrapperClass());
    }

    public function testRibbonComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $label, $theme, $size, $url.

        $html = $this->renderComponent(
            '<x-adminlte-ribbon id="rbn" label="Sale" theme="success"
                size="xl" url="/sales" class="extra-cls"/>'
        );

        $this->assertStringContainsString('class="ribbon-wrapper ribbon-xl extra-cls"', $html);
        $this->assertStringContainsString('id="rbn"', $html);
        $this->assertStringContainsString('class="ribbon text-bg-success"', $html);
        $this->assertStringContainsString('<a href="/sales">Sale</a>', $html);

        $this->assertV4Markup($html);
    }

    public function testRibbonComponentWithoutUrlDoesNotRenderALink()
    {
        $html = $this->renderComponent('<x-adminlte-ribbon label="New"/>');

        $this->assertStringNotContainsString('<a ', $html);
        $this->assertStringContainsString('New', $html);
    }

    public function testRibbonComponentSlotOverridesTheLabel()
    {
        $html = $this->renderComponent(
            '<x-adminlte-ribbon label="Ignored"><b>Hot</b></x-adminlte-ribbon>'
        );

        $this->assertStringNotContainsString('Ignored', $html);
        $this->assertStringContainsString('<b>Hot</b>', $html);

        // The slot is also honored when the ribbon wraps a link.

        $html = $this->renderComponent(
            '<x-adminlte-ribbon label="Ignored" url="/u"><b>Hot</b></x-adminlte-ribbon>'
        );

        $this->assertStringNotContainsString('Ignored', $html);
        $this->assertStringContainsString('<a href="/u"><b>Hot</b></a>', $html);
    }

    public function testRibbonComponentEscapesTheLabel()
    {
        $component = new Components\Widget\Ribbon('Tom &amp; Jerry');

        // The html entities of the label are decoded on the component and
        // escaped back by the blade template.

        $this->assertEquals('Tom & Jerry', $component->label);

        $html = $this->renderComponent(
            '<x-adminlte-ribbon label="Tom &amp; Jerry &lt;b&gt;x&lt;/b&gt;"/>'
        );

        $this->assertStringContainsString('Tom &amp; Jerry', $html);
        $this->assertStringContainsString('&lt;b&gt;x&lt;/b&gt;', $html);
        $this->assertStringNotContainsString('<b>x</b>', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Progress group component tests.
    |--------------------------------------------------------------------------
    */

    public function testProgressGroupComponent()
    {
        $component = new Components\Widget\ProgressGroup();

        $this->assertNull($component->label);
        $this->assertEquals(0, $component->value);
        $this->assertEquals(100, $component->max);
        $this->assertEquals('primary', $component->theme);
        $this->assertEquals('sm', $component->size);
        $this->assertEquals(0, $component->makePercentage());
    }

    public function testProgressGroupComponentPercentage()
    {
        // The percentage is the value/max ratio, always in the range [0, 100].

        $component = new Components\Widget\ProgressGroup(null, 160, 200);
        $this->assertEquals(80, $component->makePercentage());

        $component = new Components\Widget\ProgressGroup(null, 1, 3);
        $this->assertEquals(33, $component->makePercentage());

        $component = new Components\Widget\ProgressGroup(null, 2, 3);
        $this->assertEquals(67, $component->makePercentage());

        $component = new Components\Widget\ProgressGroup(null, 200, 200);
        $this->assertEquals(100, $component->makePercentage());

        $component = new Components\Widget\ProgressGroup(null, 500, 200);
        $this->assertEquals(100, $component->makePercentage());

        $component = new Components\Widget\ProgressGroup(null, -20, 200);
        $this->assertEquals(0, $component->makePercentage());

        // A non positive maximum gives an empty bar instead of a division by
        // zero error.

        $component = new Components\Widget\ProgressGroup(null, 20, 0);
        $this->assertEquals(0, $component->makePercentage());

        $component = new Components\Widget\ProgressGroup(null, 20, -5);
        $this->assertEquals(0, $component->makePercentage());
    }

    public function testProgressGroupComponentBarTheme()
    {
        $component = new Components\Widget\ProgressGroup(
            null, 0, 100, 'danger'
        );

        $this->assertEquals('danger', $component->makeBarTheme());

        // The AdminLTE v3 color names are translated.

        $component = new Components\Widget\ProgressGroup(
            null, 0, 100, 'maroon'
        );

        $this->assertEquals('pink', $component->makeBarTheme());

        // An empty theme inherits the color of the container.

        $component = new Components\Widget\ProgressGroup(null, 0, 100, '');

        $this->assertEquals('', $component->makeBarTheme());
    }

    public function testProgressGroupComponentBarLabel()
    {
        // Without a label, the progress bar falls back to the translated
        // generic label.

        $component = new Components\Widget\ProgressGroup();

        $this->assertEquals(
            __('adminlte::adminlte.progress'), $component->makeBarLabel()
        );

        $component = new Components\Widget\ProgressGroup('Cart');

        $this->assertEquals('Cart', $component->makeBarLabel());
    }

    public function testProgressGroupComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $label, $value, $max, $theme, $size.

        $html = $this->renderComponent(
            '<x-adminlte-progress-group label="Add Products to Cart"
                :value="160" :max="200" theme="success" size="xs"
                class="extra-cls"/>'
        );

        $this->assertStringContainsString('class="progress-group extra-cls"', $html);
        $this->assertStringContainsString('Add Products to Cart', $html);
        $this->assertStringContainsString('class="float-end"', $html);
        $this->assertStringContainsString('<b>160</b>/200', $html);

        // The bar is a nested progress component.

        $this->assertStringContainsString('class="progress progress-xs mb-0"', $html);
        $this->assertStringContainsString('bg-success', $html);
        $this->assertStringContainsString('aria-valuenow="80"', $html);
        $this->assertStringContainsString('width:80%', $html);
        $this->assertStringContainsString('aria-label="Add Products to Cart"', $html);

        $this->assertV4Markup($html);
    }

    public function testProgressGroupComponentUsesASmallBarByDefault()
    {
        $html = $this->renderComponent('<x-adminlte-progress-group label="L"/>');

        $this->assertStringContainsString('class="progress progress-sm mb-0"', $html);

        // The bar of a group provides no bottom margin of its own, the
        // '.progress-group' container is the one adding the spacing.

        $this->assertStringNotContainsString('mb-2', $html);
    }

    public function testProgressGroupComponentWithAnIdSetupTheNestedBarId()
    {
        $html = $this->renderComponent(
            '<x-adminlte-progress-group id="pGroup" label="L" :value="50"/>'
        );

        $this->assertStringContainsString('id="pGroup"', $html);
        $this->assertStringContainsString('id="progress-pGroup"', $html);

        // Without an id, no empty id attribute is rendered on the bar.

        $html = $this->renderComponent('<x-adminlte-progress-group label="L"/>');

        $this->assertStringNotContainsString('id=', $html);
    }

    public function testProgressGroupComponentSlotOverridesTheValueText()
    {
        $html = $this->renderComponent(
            '<x-adminlte-progress-group label="L" :value="160" :max="200">
                <b>80</b>%</x-adminlte-progress-group>'
        );

        $this->assertStringNotContainsString('<b>160</b>/200', $html);
        $this->assertStringContainsString('<b>80</b>%', $html);

        // The slot does not affect the computed percentage.

        $this->assertStringContainsString('aria-valuenow="80"', $html);
    }

    public function testProgressGroupComponentWithoutThemeInheritsTheContainerColor()
    {
        $html = $this->renderComponent(
            '<x-adminlte-progress-group label="L" theme="" :value="40"/>'
        );

        $this->assertStringContainsString('class="progress-bar fw-bold"', $html);
    }

    public function testProgressGroupComponentEscapesTheLabel()
    {
        $component = new Components\Widget\ProgressGroup('Tom &amp; Jerry');

        $this->assertEquals('Tom & Jerry', $component->label);

        $html = $this->renderComponent(
            '<x-adminlte-progress-group label="&lt;b&gt;x&lt;/b&gt;"/>'
        );

        $this->assertStringContainsString('&lt;b&gt;x&lt;/b&gt;', $html);
        $this->assertStringNotContainsString('<b>x</b>', $html);
    }

    public function testProgressGroupComponentRegistersTheJavascriptHelper()
    {
        $this->renderComponent('<x-adminlte-progress-group id="pg" label="L"/>');

        $js = $this->renderPushedAssets();

        $this->assertStringContainsString('class _AdminLTE_Progress', $js);
        $this->assertFreeOfJquery($js);
    }

    /*
    |--------------------------------------------------------------------------
    | User block component tests.
    |--------------------------------------------------------------------------
    */

    public function testUserBlockComponent()
    {
        $component = new Components\Widget\UserBlock(
            'Name', '/i.png', 'Desc', '/u'
        );

        $this->assertEquals('Name', $component->name);
        $this->assertEquals('/i.png', $component->img);
        $this->assertEquals('Desc', $component->description);
        $this->assertEquals('/u', $component->url);
        $this->assertEquals('user-block', $component->makeUserBlockClass());
    }

    public function testUserBlockComponentSizes()
    {
        $component = new Components\Widget\UserBlock(
            null, null, null, null, 'sm'
        );

        $this->assertStringContainsString(
            'user-block-sm', $component->makeUserBlockClass()
        );

        // An unsupported size is ignored.

        $component = new Components\Widget\UserBlock(
            null, null, null, null, 'lg'
        );

        $this->assertEquals('user-block', $component->makeUserBlockClass());
    }

    public function testUserBlockComponentRendersEveryAttribute()
    {
        // Test all the constructor arguments at once:
        // $name, $img, $description, $url, $size.

        $html = $this->renderComponent(
            '<x-adminlte-user-block id="ub" name="Maria Gonzales"
                img="/img/user.png" description="Posted 5 minutes ago"
                url="/profile" size="sm" class="mb-3">A comment
            </x-adminlte-user-block>'
        );

        $this->assertStringContainsString('class="user-block user-block-sm mb-3"', $html);
        $this->assertStringContainsString('id="ub"', $html);
        $this->assertStringContainsString(
            '<img class="rounded-circle" src="/img/user.png" alt="Maria Gonzales">', $html
        );
        $this->assertStringContainsString('class="username"', $html);
        $this->assertStringContainsString('<a href="/profile">Maria Gonzales</a>', $html);
        $this->assertStringContainsString(
            '<span class="description">Posted 5 minutes ago</span>', $html
        );
        $this->assertStringContainsString('class="comment"', $html);
        $this->assertStringContainsString('A comment', $html);

        $this->assertV4Markup($html);
    }

    public function testUserBlockComponentWithoutUrlDoesNotRenderALink()
    {
        $html = $this->renderComponent('<x-adminlte-user-block name="Name"/>');

        $this->assertStringContainsString('class="username"', $html);
        $this->assertStringContainsString('Name', $html);
        $this->assertStringNotContainsString('<a ', $html);
    }

    public function testUserBlockComponentOmitsTheEmptySections()
    {
        $html = $this->renderComponent('<x-adminlte-user-block/>');

        $this->assertStringContainsString('class="user-block"', $html);
        $this->assertStringNotContainsString('<img', $html);
        $this->assertStringNotContainsString('username', $html);
        $this->assertStringNotContainsString('description', $html);
        $this->assertStringNotContainsString('comment', $html);
    }

    public function testUserBlockComponentImageWithoutNameHasAnEmptyAlt()
    {
        $html = $this->renderComponent('<x-adminlte-user-block img="/i.png"/>');

        $this->assertStringContainsString('alt=""', $html);
    }

    public function testUserBlockComponentEscapesTheTextAttributes()
    {
        $component = new Components\Widget\UserBlock(
            'Tom &amp; Jerry', null, 'A &amp; B'
        );

        $this->assertEquals('Tom & Jerry', $component->name);
        $this->assertEquals('A & B', $component->description);

        $html = $this->renderComponent(
            '<x-adminlte-user-block name="&lt;b&gt;x&lt;/b&gt;"
                description="&lt;i&gt;y&lt;/i&gt;"/>'
        );

        $this->assertStringContainsString('&lt;b&gt;x&lt;/b&gt;', $html);
        $this->assertStringContainsString('&lt;i&gt;y&lt;/i&gt;', $html);
        $this->assertStringNotContainsString('<b>x</b>', $html);
        $this->assertStringNotContainsString('<i>y</i>', $html);
    }
}
