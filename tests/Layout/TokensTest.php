<?php

use JeroenNoten\LaravelAdminLte\Layout\Tokens;

class TokensTest extends TestCase
{
    public function testTheLayoutClassNames()
    {
        // The vocabulary of the AdminLTE v4 distribution. Changing any of
        // these values means changing the rendered markup.

        $this->assertEquals('app-wrapper', Tokens::WRAPPER);
        $this->assertEquals('app-sidebar', Tokens::SIDEBAR);
        $this->assertEquals('app-main', Tokens::CONTENT_WRAPPER);
        $this->assertEquals('layout-fixed', Tokens::LAYOUT_FIXED);
        $this->assertEquals('fixed-header', Tokens::FIXED_NAVBAR);
        $this->assertEquals('fixed-footer', Tokens::FIXED_FOOTER);
        $this->assertEquals('sidebar-expand-', Tokens::SIDEBAR_EXPAND_PREFIX);
        $this->assertEquals('sidebar-mini', Tokens::SIDEBAR_MINI);
        $this->assertEquals('sidebar-collapse', Tokens::SIDEBAR_COLLAPSE);
        $this->assertEquals('sidebar-without-hover', Tokens::SIDEBAR_WITHOUT_HOVER);
    }

    public function testTheLayoutAttributeNames()
    {
        $this->assertEquals('data-bs-theme', Tokens::COLOR_MODE_ATTRIBUTE);
        $this->assertEquals('data-lte-color-mode', Tokens::COLOR_MODE_DISABLED_ATTRIBUTE);
        $this->assertEquals('data-enable-persistence', Tokens::SIDEBAR_PERSISTENCE_ATTRIBUTE);
    }

    public function testTheSupportedTokenSets()
    {
        $this->assertEquals(
            ['sm', 'md', 'lg', 'xl', 'xxl'],
            Tokens::SIDEBAR_EXPAND_BREAKPOINTS
        );

        $this->assertEquals(['light', 'dark', 'auto'], Tokens::COLOR_MODES);

        // The legacy 'sidebar_mini' option also accepted the 'xs' token.

        $this->assertEquals(
            ['xs', 'sm', 'md', 'lg', 'xl', 'xxl'],
            Tokens::LEGACY_SIDEBAR_MINI_TOKENS
        );
    }

    public function testSidebarExpandWithTheSupportedBreakpoints()
    {
        foreach (Tokens::SIDEBAR_EXPAND_BREAKPOINTS as $breakpoint) {
            $this->assertEquals(
                "sidebar-expand-{$breakpoint}",
                Tokens::sidebarExpand($breakpoint)
            );
        }
    }

    public function testSidebarExpandWithUnsupportedValues()
    {
        // The 'xs' token is a legacy one, the sidebar can not expand on it.

        $this->assertNull(Tokens::sidebarExpand('xs'));
        $this->assertNull(Tokens::sidebarExpand('invalid'));
        $this->assertNull(Tokens::sidebarExpand(''));

        // The comparison is case sensitive and strict, so no type juggling is
        // expected on the resulting class.

        $this->assertNull(Tokens::sidebarExpand('LG'));
        $this->assertNull(Tokens::sidebarExpand(null));
        $this->assertNull(Tokens::sidebarExpand(true));
        $this->assertNull(Tokens::sidebarExpand(0));
        $this->assertNull(Tokens::sidebarExpand(['lg']));
    }
}
