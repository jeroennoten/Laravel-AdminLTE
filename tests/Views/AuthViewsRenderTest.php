<?php

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;

class AuthViewsRenderTest extends TestCase
{
    /**
     * Renders one of the authentication views and returns the html.
     *
     * @param  string  $view  The name of the authentication view
     * @return string
     */
    protected function renderAuthView($view)
    {
        // On a real request the error bag is shared by the web middleware.

        View::share('errors', new ViewErrorBag());
        View::flushSections();

        return View::make("adminlte::auth.{$view}")->render();
    }

    /**
     * Gets the separator label used by the social authentication links.
     *
     * @return string
     */
    protected function getSeparatorLabel()
    {
        $key = 'adminlte::adminlte.social_auth_separator';

        return Lang::has($key) ? __($key) : '- OR -';
    }

    /**
     * Gets the html of the social authentication links block.
     *
     * @param  string  $html  The html of an authentication view
     * @return string
     */
    protected function getSocialLinksBlock($html)
    {
        $pattern = '/<div class="social-auth-links.*?<\/div>/s';

        return preg_match($pattern, $html, $m) ? $m[0] : '';
    }

    public function testNoSocialAuthLinksAreRenderedByDefault()
    {
        foreach (['login', 'register'] as $view) {
            $html = $this->renderAuthView($view);

            $this->assertStringNotContainsString('social-auth-links', $html);
            $this->assertStringNotContainsString('<p>'.$this->getSeparatorLabel().'</p>', $html);
        }
    }

    public function testNoSocialAuthLinksAreRenderedWhenTheConfigIsEmpty()
    {
        config(['adminlte.auth_social_links' => []]);

        foreach (['login', 'register'] as $view) {
            $this->assertStringNotContainsString(
                'social-auth-links', $this->renderAuthView($view)
            );
        }
    }

    public function testRenderOneSocialAuthLink()
    {
        config(['adminlte.auth_social_links' => [
            [
                'url' => 'https://example.com/auth/facebook',
                'text' => 'Sign in using Facebook',
                'icon' => 'bi bi-facebook',
            ],
        ]]);

        $block = $this->getSocialLinksBlock($this->renderAuthView('login'));

        // The block is rendered with the classes of the AdminLTE v4 markup.

        $this->assertStringContainsString(
            '<div class="social-auth-links text-center mb-3 d-grid gap-2">', $block
        );

        $this->assertStringContainsString('<p>'.$this->getSeparatorLabel().'</p>', $block);
        $this->assertStringContainsString('href="https://example.com/auth/facebook"', $block);
        $this->assertStringContainsString('class="btn btn-primary"', $block);
        $this->assertStringContainsString('<i class="bi bi-facebook me-2"></i>', $block);
        $this->assertStringContainsString('Sign in using Facebook', $block);
        $this->assertSame(1, substr_count($block, '<a href='));
    }

    public function testRenderSeveralSocialAuthLinks()
    {
        config(['adminlte.auth_social_links' => [
            [
                'url' => 'https://example.com/auth/facebook',
                'text' => 'Facebook',
                'icon' => 'bi bi-facebook',
                'theme' => 'primary',
            ],
            [
                'url' => 'https://example.com/auth/google',
                'text' => 'Google',
                'icon' => 'bi bi-google',
                'theme' => 'danger',
            ],
            [
                'url' => 'https://example.com/auth/github',
                'text' => 'Github',
                'icon' => 'bi bi-github',
                'theme' => 'outline-dark',
            ],
        ]]);

        $block = $this->getSocialLinksBlock($this->renderAuthView('login'));

        $this->assertSame(3, substr_count($block, '<a href='));
        $this->assertStringContainsString('class="btn btn-primary"', $block);
        $this->assertStringContainsString('class="btn btn-danger"', $block);
        $this->assertStringContainsString('class="btn btn-outline-dark"', $block);

        // The links keep the order of the configuration.

        $this->assertLessThan(
            strpos($block, 'Google'), strpos($block, 'Facebook')
        );

        $this->assertLessThan(
            strpos($block, 'Github'), strpos($block, 'Google')
        );
    }

    public function testTheSocialAuthLinksAreRenderedOnLoginAndRegister()
    {
        config(['adminlte.auth_social_links' => [
            ['url' => 'https://example.com/auth/facebook', 'text' => 'Facebook'],
        ]]);

        foreach (['login', 'register'] as $view) {
            $html = $this->renderAuthView($view);

            $this->assertStringContainsString('social-auth-links', $html);
            $this->assertStringContainsString('href="https://example.com/auth/facebook"', $html);

            // The block belongs to the card body, next to the form.

            $this->assertLessThan(
                strpos($html, 'social-auth-links'), strpos($html, '</form>')
            );
        }
    }

    public function testTheSocialAuthLinksAreNotRenderedOnTheOtherAuthViews()
    {
        config(['adminlte.auth_social_links' => [
            ['url' => 'https://example.com/auth/facebook', 'text' => 'Facebook'],
        ]]);

        $this->assertStringNotContainsString(
            'social-auth-links', $this->renderAuthView('passwords.email')
        );
    }

    public function testTheSocialAuthLinkValuesAreEscaped()
    {
        config(['adminlte.auth_social_links' => [
            [
                'url' => 'https://example.com/auth?a=1&b="><script>alert(1)</script>',
                'text' => '<script>alert("xss")</script>',
                'icon' => 'bi bi-facebook',
            ],
        ]]);

        $block = $this->getSocialLinksBlock($this->renderAuthView('login'));

        $this->assertStringNotContainsString('<script>', $block);
        $this->assertStringContainsString('&lt;script&gt;', $block);
        $this->assertStringContainsString('&quot;', $block);
        $this->assertStringContainsString('&amp;', $block);
    }

    public function testTheInvalidSocialAuthLinkIconsAreRejected()
    {
        $icons = [
            'bi" onclick="alert(1)',
            'bi bi-facebook"><script>alert(1)</script>',
            'bi bi-facebook; color:red',
            'a b c d e f g h',
        ];

        foreach ($icons as $icon) {
            config(['adminlte.auth_social_links' => [
                ['url' => 'https://example.com/auth', 'text' => 'Facebook', 'icon' => $icon],
            ]]);

            $block = $this->getSocialLinksBlock($this->renderAuthView('login'));

            $this->assertStringNotContainsString('<i class=', $block);
            $this->assertStringContainsString('Facebook', $block);
        }
    }

    public function testTheInvalidSocialAuthLinkThemesAreRejected()
    {
        $themes = [
            'danger" onclick="alert(1)',
            'primary d-none',
            'btn-primary',
            'my-theme',
            '',
        ];

        foreach ($themes as $theme) {
            config(['adminlte.auth_social_links' => [
                ['url' => 'https://example.com/auth', 'text' => 'Facebook', 'theme' => $theme],
            ]]);

            $block = $this->getSocialLinksBlock($this->renderAuthView('login'));

            $this->assertStringContainsString('class="btn btn-primary"', $block);
        }
    }

    public function testTheSocialAuthLinksWithoutUrlAreIgnored()
    {
        config(['adminlte.auth_social_links' => [
            ['text' => 'No url'],
            ['url' => '', 'text' => 'Empty url'],
            ['url' => ['array'], 'text' => 'Invalid url'],
            ['url' => 'https://example.com/auth', 'text' => 'Facebook'],
        ]]);

        $block = $this->getSocialLinksBlock($this->renderAuthView('login'));

        $this->assertSame(1, substr_count($block, '<a href='));
        $this->assertStringNotContainsString('No url', $block);
        $this->assertStringNotContainsString('Empty url', $block);
        $this->assertStringNotContainsString('Invalid url', $block);
    }

    public function testTheSocialAuthLinksUseAFallbackText()
    {
        config(['adminlte.auth_social_links' => [
            ['url' => 'https://example.com/auth'],
        ]]);

        $this->assertStringContainsString(
            __('adminlte::adminlte.sign_in'),
            $this->getSocialLinksBlock($this->renderAuthView('login'))
        );

        $this->assertStringContainsString(
            __('adminlte::adminlte.register'),
            $this->getSocialLinksBlock($this->renderAuthView('register'))
        );
    }

    public function testTheSocialAuthLinksSeparatorCanBeChanged()
    {
        config([
            'adminlte.auth_social_links' => [['url' => 'https://example.com/auth']],
            'adminlte.auth_social_links_separator' => '<b>or</b>',
        ]);

        $block = $this->getSocialLinksBlock($this->renderAuthView('login'));

        $this->assertStringContainsString('<p>&lt;b&gt;or&lt;/b&gt;</p>', $block);
        $this->assertSame(1, substr_count($block, '<p>'));
    }

    public function testTheSocialAuthLinksSeparatorCanBeDisabled()
    {
        config([
            'adminlte.auth_social_links' => [['url' => 'https://example.com/auth']],
            'adminlte.auth_social_links_separator' => '',
        ]);

        $block = $this->getSocialLinksBlock($this->renderAuthView('login'));

        $this->assertStringNotContainsString('<p>', $block);
        $this->assertStringContainsString('<a href=', $block);
    }

    public function testTheSocialAuthLinksSeparatorUsesTheTranslation()
    {
        app('translator')->addLines(
            ['adminlte.social_auth_separator' => 'oder'], 'en', 'adminlte'
        );

        config(['adminlte.auth_social_links' => [['url' => 'https://example.com/auth']]]);

        $this->assertStringContainsString(
            '<p>oder</p>',
            $this->getSocialLinksBlock($this->renderAuthView('login'))
        );
    }

    public function testTheSocialAuthLinksRejectAnUnsafeUrlScheme()
    {
        // The urls come from the configuration file, but a scheme allowlist
        // keeps a 'javascript:' target out of the markup anyway.

        config(['adminlte.auth_social_links' => [
            ['url' => 'javascript:alert(1)', 'text' => 'Evil'],
            ['url' => 'JaVaScRiPt:alert(1)', 'text' => 'EvilToo'],
            ['url' => 'data:text/html,x', 'text' => 'EvilData'],
            ['url' => ' javascript:alert(1)', 'text' => 'EvilSpaced'],
            ['url' => 'https://example.test/auth', 'text' => 'Good'],
        ]]);

        $html = $this->renderAuthView('login');

        $this->assertStringContainsString('https://example.test/auth', $html);

        // Check the emitted hrefs, not the whole document: the inline scripts
        // of the layout legitimately mention the word.

        preg_match_all('/<a[^>]+href="([^"]*)"/', $html, $matches);

        foreach ($matches[1] as $href) {
            $this->assertDoesNotMatchRegularExpression(
                '/^\s*(javascript|data)\s*:/i',
                $href,
                $href
            );
        }

        foreach (['Evil'] as $rejected) {
            $this->assertStringNotContainsString($rejected, $html, $rejected);
        }
    }

    public function testTheSocialAuthLinksAcceptTheUsualTargets()
    {
        config(['adminlte.auth_social_links' => [
            ['url' => 'https://example.test/a', 'text' => 'Https'],
            ['url' => 'http://example.test/b', 'text' => 'Http'],
            ['url' => '/auth/facebook', 'text' => 'Absolute path'],
            ['url' => 'auth/google', 'text' => 'Relative path'],
        ]]);

        $html = $this->renderAuthView('login');

        foreach (['Https', 'Http', 'Absolute path', 'Relative path'] as $kept) {
            $this->assertStringContainsString($kept, $html, $kept);
        }
    }

    public function testThePasswordEmailViewOffersAWayBack()
    {
        // Without these links the page is a dead end: the reference layout of
        // AdminLTE provides both of them.

        $html = $this->renderAuthView('passwords.email');

        $this->assertStringContainsString(
            __('adminlte::adminlte.i_already_have_a_membership'),
            $html
        );
        $this->assertStringContainsString(
            __('adminlte::adminlte.register_a_new_membership'),
            $html
        );
        $this->assertStringContainsString(url('login'), $html);
    }

    public function testThePasswordEmailBackLinksFollowTheConfiguration()
    {
        config([
            'adminlte.use_route_url' => false,
            'adminlte.login_url' => 'my-login',
            'adminlte.register_url' => '',
        ]);

        $html = $this->renderAuthView('passwords.email');

        $this->assertStringContainsString(url('my-login'), $html);
        $this->assertStringNotContainsString(
            __('adminlte::adminlte.register_a_new_membership'),
            $html
        );
    }
}
