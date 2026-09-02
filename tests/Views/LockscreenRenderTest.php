<?php

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class LockscreenRenderTest extends TestCase
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
     * Renders the lockscreen view of the package.
     *
     * @param  array  $data  The data passed by the lockscreen controller
     * @return string
     */
    protected function renderLockscreen($data = [])
    {
        View::flushSections();

        // On a real request the error bag is shared by the web middleware.

        if (! View::shared('errors')) {
            View::share('errors', new ViewErrorBag());
        }

        $data = array_merge([
            'user' => new LockscreenRenderTestUser(),
            'unlockUrl' => url('adminlte/lockscreen/unlock'),
        ], $data);

        return View::make('adminlte::auth.lockscreen', $data)->render();
    }

    public function testRenderTheLockscreenMarkup()
    {
        $html = $this->renderLockscreen();

        // Check the AdminLTE v4 lockscreen skeleton is rendered.

        $this->assertStringContainsString('class="lockscreen bg-body-secondary"', $html);
        $this->assertStringContainsString('<main class="lockscreen-wrapper">', $html);
        $this->assertStringContainsString('lockscreen-logo', $html);
        $this->assertStringContainsString('lockscreen-name', $html);
        $this->assertStringContainsString('lockscreen-item', $html);
        $this->assertStringContainsString('lockscreen-credentials', $html);
        $this->assertStringContainsString('lockscreen-footer', $html);

        // Check the form posts the password to the unlock endpoint.

        $this->assertStringContainsString(url('adminlte/lockscreen/unlock'), $html);
        $this->assertStringContainsString('type="password"', $html);
        $this->assertStringContainsString('name="_token"', $html);
        $this->assertStringContainsString('Jane Doe', $html);

        // Check no AdminLTE v3 markup survived.

        foreach (['input-group-append', 'input-group-prepend', 'fas fa-', 'help-block'] as $legacy) {
            $this->assertStringNotContainsString($legacy, $html);
        }

        // The directives of the view have to be compiled, a literal one means
        // the blade compilation of the file went wrong.

        foreach (['@if', '@endif', '@php', '@error', '@csrf'] as $directive) {
            $this->assertStringNotContainsString($directive, $html);
        }
    }

    public function testRenderTheUserImageWhenItIsEnabled()
    {
        config(['adminlte.usermenu_image' => true]);

        $html = $this->renderLockscreen();

        $this->assertStringContainsString('lockscreen-image', $html);
        $this->assertStringContainsString('user-avatar.jpg', $html);
        $this->assertStringNotContainsString('ms-0', $html);
    }

    public function testRenderWithoutTheUserImage()
    {
        config(['adminlte.usermenu_image' => false]);

        $html = $this->renderLockscreen();

        $this->assertStringNotContainsString('lockscreen-image', $html);
        $this->assertStringContainsString('ms-0', $html);
    }

    public function testRenderTheEmailWhenTheUserHasNoName()
    {
        $user = new LockscreenRenderTestUser();
        $user->name = null;

        $html = $this->renderLockscreen(['user' => $user]);

        $this->assertStringContainsString('jane.doe@example.com', $html);
    }

    public function testRenderThePasswordError()
    {
        $errors = new ViewErrorBag();
        $errors->put('default', new MessageBag(['password' => ['Wrong password.']]));

        View::share('errors', $errors);

        $html = $this->renderLockscreen();

        $this->assertStringContainsString('is-invalid', $html);
        $this->assertStringContainsString('Wrong password.', $html);
        $this->assertStringContainsString('role="alert"', $html);
    }

    public function testRenderTheLogoutFormOfTheFooter()
    {
        config([
            'adminlte.use_route_url' => false,
            'adminlte.logout_url' => 'my-logout',
            'adminlte.logout_method' => 'DELETE',
        ]);

        $html = $this->renderLockscreen();

        $this->assertStringContainsString(url('my-logout'), $html);
        $this->assertStringContainsString('value="DELETE"', $html);
    }

    public function testRenderTheLogoOfTheConfiguration()
    {
        config([
            'adminlte.use_route_url' => false,
            'adminlte.dashboard_url' => 'my-dashboard',
            'adminlte.logo' => '<b>My</b>Panel',
        ]);

        $html = $this->renderLockscreen();

        $this->assertStringContainsString(url('my-dashboard'), $html);
        $this->assertStringContainsString('<b>My</b>Panel', $html);
    }

    public function testRenderTheAuthenticatedUserWhenNoneIsGiven()
    {
        Auth::setUser(new LockscreenRenderTestUser());

        View::flushSections();

        if (! View::shared('errors')) {
            View::share('errors', new ViewErrorBag());
        }

        $html = View::make('adminlte::auth.lockscreen')->render();

        $this->assertStringContainsString('Jane Doe', $html);
        $this->assertStringContainsString('lockscreen-credentials', $html);
    }
}

class LockscreenRenderTestUser implements Authenticatable
{
    /**
     * The name of the dummy user.
     *
     * @var string|null
     */
    public $name = 'Jane Doe';

    /**
     * The email of the dummy user.
     *
     * @var string
     */
    public $email = 'jane.doe@example.com';

    /**
     * Get the avatar of the dummy user.
     *
     * @return string
     */
    public function adminlte_image()
    {
        return 'https://picsum.photos/user-avatar.jpg';
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
        return null;
    }

    public function setRememberToken($value)
    {
        //
    }

    public function getRememberTokenName()
    {
        return null;
    }
}
