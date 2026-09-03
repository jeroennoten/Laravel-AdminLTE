<?php

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class UserMenuRenderTest extends TestCase
{
    /**
     * Tear down this testing class.
     */
    public function tearDown(): void
    {
        View::flushSections();

        parent::tearDown();
    }

    /**
     * Authenticates a dummy user, so the navbar renders the user menu.
     *
     * @return void
     */
    protected function loginDummyUser()
    {
        Auth::setUser(new UserMenuRenderTestUser());
    }

    /**
     * Renders the package page layout.
     *
     * @return string
     */
    protected function renderPage()
    {
        View::flushSections();

        return View::make('adminlte::page')->render();
    }

    public function testRenderTheUserMenuOfAnAuthenticatedUser()
    {
        config(['adminlte.usermenu_enabled' => true]);

        $this->loginDummyUser();

        $html = $this->renderPage();

        $this->assertStringContainsString('user-menu', $html);
        $this->assertStringContainsString('Jane Doe', $html);

        // The directives of the partial have to be compiled, a literal one
        // means the blade compilation of the file went wrong.

        $this->assertStringNotContainsString('@else', $html);
        $this->assertStringNotContainsString('@endif', $html);
        $this->assertStringNotContainsString('@php', $html);
    }

    public function testTheUserMenuResolvesTheConfiguredUrls()
    {
        config([
            'adminlte.usermenu_enabled' => true,
            'adminlte.use_route_url' => false,
            'adminlte.logout_url' => 'my-logout',
            'adminlte.profile_url' => 'my-profile',
        ]);

        $this->loginDummyUser();

        $html = $this->renderPage();

        // Both branches of the url resolution used to be swallowed by the
        // blade compiler, so the urls never reached the markup.

        $this->assertStringContainsString(url('my-logout'), $html);
        $this->assertStringContainsString(url('my-profile'), $html);
    }

    public function testTheUserMenuHeaderClassTranslatesTheBootstrapColors()
    {
        config([
            'adminlte.usermenu_enabled' => true,
            'adminlte.usermenu_header' => true,
            'adminlte.usermenu_header_class' => 'bg-primary',
        ]);

        $this->loginDummyUser();

        $html = $this->renderPage();

        $this->assertStringContainsString('text-bg-primary', $html);
    }

    public function testTheUserMenuUsesTheOptionalUserModelMethods()
    {
        config([
            'adminlte.usermenu_enabled' => true,
            'adminlte.usermenu_header' => true,
            'adminlte.usermenu_image' => true,
            'adminlte.usermenu_desc' => true,
            'adminlte.usermenu_profile_url' => true,
        ]);

        $this->loginDummyUser();

        $html = $this->renderPage();

        $this->assertStringContainsString('/img/user.png', $html);
        $this->assertStringContainsString('Web Developer', $html);
        $this->assertStringContainsString(url('profile'), $html);
    }

    public function testTheUserMenuRendersOnAUserModelWithoutTheOptionalMethods()
    {
        // The 'adminlte_*' methods are an optional addition to the user model,
        // so enabling their options on a plain model has to degrade instead of
        // breaking every page of the panel.

        config([
            'adminlte.usermenu_enabled' => true,
            'adminlte.usermenu_header' => true,
            'adminlte.usermenu_image' => true,
            'adminlte.usermenu_desc' => true,
            'adminlte.usermenu_profile_url' => true,
        ]);

        Auth::setUser(new UserMenuRenderTestPlainUser());

        $html = $this->renderPage();

        $this->assertStringContainsString('user-menu', $html);
        $this->assertStringContainsString('John Doe', $html);
        $this->assertStringNotContainsString('user-image', $html);
    }

    public function testTheLogoutLinkIsRenderedWithoutTheUserMenu()
    {
        config(['adminlte.usermenu_enabled' => false]);

        $this->loginDummyUser();

        $html = $this->renderPage();

        $this->assertStringNotContainsString('user-menu', $html);
        $this->assertStringContainsString('logout', $html);
    }
}

class UserMenuRenderTestUser implements Authenticatable
{
    public $name = 'Jane Doe';

    public function adminlte_image()
    {
        return '/img/user.png';
    }

    public function adminlte_desc()
    {
        return 'Web Developer';
    }

    public function adminlte_profile_url()
    {
        return 'profile';
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return 1;
    }

    public function getAuthPasswordName()
    {
        return 'password';
    }

    public function getAuthPassword()
    {
        return '';
    }

    public function getRememberToken()
    {
        return '';
    }

    public function setRememberToken($value)
    {
        //
    }

    public function getRememberTokenName()
    {
        return '';
    }
}

/**
 * A user model that does not provide any of the optional 'adminlte_*' methods.
 */
class UserMenuRenderTestPlainUser implements Authenticatable
{
    public $name = 'John Doe';

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return 2;
    }

    public function getAuthPasswordName()
    {
        return 'password';
    }

    public function getAuthPassword()
    {
        return '';
    }

    public function getRememberToken()
    {
        return '';
    }

    public function setRememberToken($value)
    {
        //
    }

    public function getRememberTokenName()
    {
        return '';
    }
}
