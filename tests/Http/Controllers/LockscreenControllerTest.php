<?php

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use JeroenNoten\LaravelAdminLte\Events\ScreenWasLocked;
use JeroenNoten\LaravelAdminLte\Events\ScreenWasUnlocked;
use JeroenNoten\LaravelAdminLte\Http\Controllers\LockscreenController;
use JeroenNoten\LaravelAdminLte\Http\Middleware\RedirectIfLocked;

class LockscreenControllerTest extends TestCase
{
    /**
     * The plain text password of the dummy user of these tests.
     *
     * @var string
     */
    protected $password = 'sup3r-s3cret';

    /**
     * The session key used to flag a locked screen.
     *
     * @var string
     */
    protected $sessionKey = 'adminlte_lockscreen';

    /**
     * Define the environment setup. The lockscreen routes use the 'web'
     * middleware group, that requires an application encryption key. The
     * lockscreen feature is opt-in, so it is enabled before the provider
     * registers the routes.
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));
        $app['config']->set('hashing.bcrypt.rounds', 4);
        $app['config']->set('adminlte.lockscreen.enabled', true);
        $app['config']->set('adminlte.lockscreen.routes', true);
    }

    /**
     * Make the dummy user of these tests.
     *
     * @return LockscreenTestUser
     */
    protected function makeUser()
    {
        return new LockscreenTestUser(Hash::make($this->password));
    }

    /**
     * Get the rate limiter key used for the dummy user of these tests.
     *
     * @return string
     */
    protected function throttleKey()
    {
        return 'adminlte-lockscreen|1|127.0.0.1';
    }

    public function testTheLockscreenRoutesAreRegistered()
    {
        $this->assertTrue(Route::has('adminlte.lockscreen.lock'));
        $this->assertTrue(Route::has('adminlte.lockscreen.show'));
        $this->assertTrue(Route::has('adminlte.lockscreen.unlock'));

        $lock = Route::getRoutes()->getByName('adminlte.lockscreen.lock');
        $show = Route::getRoutes()->getByName('adminlte.lockscreen.show');
        $unlock = Route::getRoutes()->getByName('adminlte.lockscreen.unlock');

        $this->assertEquals('adminlte/lockscreen/lock', $lock->uri());
        $this->assertEquals('adminlte/lockscreen', $show->uri());
        $this->assertEquals('adminlte/lockscreen/unlock', $unlock->uri());

        $this->assertContains('POST', $lock->methods());
        $this->assertContains('GET', $show->methods());
        $this->assertContains('POST', $unlock->methods());
    }

    public function testTheScreenIsNotLockedByDefault()
    {
        Session::forget($this->sessionKey);

        $this->assertFalse((new LockscreenController())->isLocked());
    }

    public function testTheLockedFlagIsSharedBetweenControllers()
    {
        Session::forget($this->sessionKey);

        (new LockscreenController())->lockScreen();

        $this->assertTrue((new LockscreenController())->isLocked());
        $this->assertTrue(session($this->sessionKey));

        (new LockscreenController())->unlockScreen();

        $this->assertFalse((new LockscreenController())->isLocked());
        $this->assertNull(session($this->sessionKey, null));
    }

    public function testTheLockRouteFlagsTheSessionAndRedirects()
    {
        $response = $this->actingAs($this->makeUser())
            ->post(route('adminlte.lockscreen.lock'));

        $response->assertRedirect(route('adminlte.lockscreen.show'));
        $response->assertSessionHas($this->sessionKey, true);
    }

    public function testTheLockRouteRemembersThePageTheUserComesFrom()
    {
        $response = $this->actingAs($this->makeUser())
            ->from(url('reports/monthly'))
            ->post(route('adminlte.lockscreen.lock'));

        $response->assertRedirect(route('adminlte.lockscreen.show'));
        $response->assertSessionHas('url.intended', url('reports/monthly'));
    }

    public function testTheLockRouteDoesNotRememberTheLockscreenItself()
    {
        $response = $this->actingAs($this->makeUser())
            ->from(route('adminlte.lockscreen.show'))
            ->post(route('adminlte.lockscreen.lock'));

        $response->assertRedirect(route('adminlte.lockscreen.show'));
        $response->assertSessionMissing('url.intended');
    }

    public function testTheLockRouteDispatchesTheLockedEvent()
    {
        Event::fake();

        $user = $this->makeUser();

        $this->actingAs($user)->post(route('adminlte.lockscreen.lock'));

        Event::assertDispatched(ScreenWasLocked::class, function ($event) use ($user) {
            return $event->lockscreen instanceof LockscreenController
                && $event->lockscreen->isLocked()
                && $event->user === $user;
        });
    }

    public function testTheLockRouteRedirectsTheGuestsToTheLoginPage()
    {
        config(['adminlte.login_url' => 'login']);

        $response = $this->post(route('adminlte.lockscreen.lock'));

        $response->assertRedirect(url('login'));
        $response->assertSessionMissing($this->sessionKey);
    }

    public function testTheShowRouteRendersTheLockscreenView()
    {
        $response = $this->actingAs($this->makeUser())
            ->withSession([$this->sessionKey => true])
            ->get(route('adminlte.lockscreen.show'));

        $response->assertOk();

        $html = $response->getContent();

        // Check the AdminLTE v4 lockscreen markup is rendered.

        $this->assertStringContainsString('class="lockscreen bg-body-secondary"', $html);
        $this->assertStringContainsString('lockscreen-wrapper', $html);
        $this->assertStringContainsString('lockscreen-logo', $html);
        $this->assertStringContainsString('lockscreen-name', $html);
        $this->assertStringContainsString('lockscreen-item', $html);
        $this->assertStringContainsString('lockscreen-credentials', $html);
        $this->assertStringContainsString('lockscreen-footer', $html);

        // Check the form targets the unlock endpoint of the package.

        $this->assertStringContainsString(route('adminlte.lockscreen.unlock'), $html);
        $this->assertStringContainsString('Jane Doe', $html);

        // The directives of the view have to be compiled, a literal one means
        // the blade compilation of the file went wrong.

        $this->assertStringNotContainsString('@if', $html);
        $this->assertStringNotContainsString('@endif', $html);
        $this->assertStringNotContainsString('@php', $html);
        $this->assertStringNotContainsString('@error', $html);
    }

    public function testTheShowRouteRedirectsWhenTheScreenIsNotLocked()
    {
        config(['adminlte.dashboard_url' => 'home']);

        $response = $this->actingAs($this->makeUser())
            ->get(route('adminlte.lockscreen.show'));

        $response->assertRedirect(url('home'));
    }

    public function testTheShowRouteRedirectsTheGuestsToTheLoginPage()
    {
        config(['adminlte.login_url' => 'login']);

        $response = $this->withSession([$this->sessionKey => true])
            ->get(route('adminlte.lockscreen.show'));

        $response->assertRedirect(url('login'));
    }

    public function testTheUnlockRouteAcceptsTheCorrectPassword()
    {
        $response = $this->actingAs($this->makeUser())
            ->withSession([
                $this->sessionKey => true,
                'url.intended' => url('reports/monthly'),
            ])
            ->post(route('adminlte.lockscreen.unlock'), [
                'password' => $this->password,
            ]);

        $response->assertRedirect(url('reports/monthly'));
        $response->assertSessionMissing($this->sessionKey);
        $response->assertSessionHasNoErrors();
    }

    public function testTheUnlockRouteFallsBackToTheDashboardUrl()
    {
        config(['adminlte.dashboard_url' => 'home']);

        $response = $this->actingAs($this->makeUser())
            ->withSession([$this->sessionKey => true])
            ->post(route('adminlte.lockscreen.unlock'), [
                'password' => $this->password,
            ]);

        $response->assertRedirect(url('home'));
        $response->assertSessionMissing($this->sessionKey);
    }

    public function testTheUnlockRouteRejectsAWrongPassword()
    {
        $response = $this->actingAs($this->makeUser())
            ->withSession([$this->sessionKey => true])
            ->post(route('adminlte.lockscreen.unlock'), [
                'password' => 'not-the-password',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('password');
        $response->assertSessionHas($this->sessionKey, true);
    }

    public function testTheUnlockRouteRequiresAPassword()
    {
        $response = $this->actingAs($this->makeUser())
            ->withSession([$this->sessionKey => true])
            ->post(route('adminlte.lockscreen.unlock'));

        $response->assertSessionHasErrors('password');
        $response->assertSessionHas($this->sessionKey, true);
    }

    public function testTheSubmittedPasswordNeverReachesTheSession()
    {
        $this->actingAs($this->makeUser())
            ->withSession([$this->sessionKey => true])
            ->post(route('adminlte.lockscreen.unlock'), [
                'password' => $this->password,
            ]);

        $this->assertStringNotContainsString(
            $this->password,
            json_encode(session()->all())
        );

        // And the same holds for a rejected attempt, where the old input of
        // the form is flashed back to the session.

        $this->actingAs($this->makeUser())
            ->withSession([$this->sessionKey => true])
            ->post(route('adminlte.lockscreen.unlock'), [
                'password' => 'not-the-password',
            ]);

        $this->assertArrayNotHasKey('password', session('_old_input', []));

        $this->assertStringNotContainsString(
            'not-the-password',
            json_encode(session()->all())
        );
    }

    public function testTheUnlockRouteDispatchesTheUnlockedEvent()
    {
        Event::fake();

        $user = $this->makeUser();

        $this->actingAs($user)
            ->withSession([$this->sessionKey => true])
            ->post(route('adminlte.lockscreen.unlock'), [
                'password' => $this->password,
            ]);

        Event::assertDispatched(ScreenWasUnlocked::class, function ($event) use ($user) {
            return $event->lockscreen instanceof LockscreenController
                && ! $event->lockscreen->isLocked()
                && $event->user === $user;
        });
    }

    public function testTheUnlockRouteDoesNotDispatchTheEventOnAWrongPassword()
    {
        Event::fake();

        $this->actingAs($this->makeUser())
            ->withSession([$this->sessionKey => true])
            ->post(route('adminlte.lockscreen.unlock'), [
                'password' => 'not-the-password',
            ]);

        Event::assertNotDispatched(ScreenWasUnlocked::class);
    }

    public function testTheUnlockRouteRedirectsTheGuestsToTheLoginPage()
    {
        config(['adminlte.login_url' => 'login']);

        $response = $this->withSession([$this->sessionKey => true])
            ->post(route('adminlte.lockscreen.unlock'), [
                'password' => $this->password,
            ]);

        $response->assertRedirect(url('login'));
        $response->assertSessionHas($this->sessionKey, true);
    }

    public function testTheUnlockAttemptsAreRateLimited()
    {
        config([
            'adminlte.lockscreen.throttle.max_attempts' => 3,
            'adminlte.lockscreen.throttle.decay_seconds' => 60,
        ]);

        $user = $this->makeUser();

        RateLimiter::clear($this->throttleKey());

        for ($i = 0; $i < 3; $i++) {
            $response = $this->actingAs($user)
                ->withSession([$this->sessionKey => true])
                ->post(route('adminlte.lockscreen.unlock'), [
                    'password' => 'not-the-password',
                ]);

            $response->assertSessionHasErrors('password');
        }

        $this->assertTrue(RateLimiter::tooManyAttempts($this->throttleKey(), 3));

        // Once the limit is reached, even the correct password is rejected
        // and the screen stays locked.

        $response = $this->actingAs($user)
            ->withSession([$this->sessionKey => true])
            ->post(route('adminlte.lockscreen.unlock'), [
                'password' => $this->password,
            ]);

        $response->assertSessionHasErrors('password');
        $response->assertSessionHas($this->sessionKey, true);

        // A throttled attempt answers with the 'Locked' status on the api
        // clients, that get the error as a json payload.

        $response = $this->actingAs($user)
            ->withSession([$this->sessionKey => true])
            ->postJson(route('adminlte.lockscreen.unlock'), [
                'password' => $this->password,
            ]);

        $response->assertStatus(429);
        $response->assertJsonValidationErrors('password');
    }

    public function testTheRateLimiterIsClearedOnASuccessfulUnlock()
    {
        config(['adminlte.lockscreen.throttle.max_attempts' => 3]);

        $user = $this->makeUser();

        RateLimiter::clear($this->throttleKey());

        $this->actingAs($user)
            ->withSession([$this->sessionKey => true])
            ->post(route('adminlte.lockscreen.unlock'), [
                'password' => 'not-the-password',
            ]);

        $this->assertEquals(1, RateLimiter::attempts($this->throttleKey()));

        $this->actingAs($user)
            ->withSession([$this->sessionKey => true])
            ->post(route('adminlte.lockscreen.unlock'), [
                'password' => $this->password,
            ]);

        $this->assertEquals(0, RateLimiter::attempts($this->throttleKey()));
    }

    public function testTheThrottlingMayBeDisabled()
    {
        config(['adminlte.lockscreen.throttle.max_attempts' => 0]);

        $user = $this->makeUser();

        RateLimiter::clear($this->throttleKey());

        for ($i = 0; $i < 6; $i++) {
            $this->actingAs($user)
                ->withSession([$this->sessionKey => true])
                ->post(route('adminlte.lockscreen.unlock'), [
                    'password' => 'not-the-password',
                ]);
        }

        // The correct password is still accepted after any amount of failed
        // attempts when the throttling is turned off.

        $response = $this->actingAs($user)
            ->withSession([$this->sessionKey => true])
            ->post(route('adminlte.lockscreen.unlock'), [
                'password' => $this->password,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionMissing($this->sessionKey);
    }

    public function testTheMiddlewareRedirectsToTheLockscreenWhileLocked()
    {
        $this->registerProtectedRoute();

        $response = $this->actingAs($this->makeUser())
            ->withSession([$this->sessionKey => true])
            ->get('/protected-page');

        $response->assertRedirect(route('adminlte.lockscreen.show'));
        $response->assertSessionHas('url.intended', url('/protected-page'));
    }

    public function testTheMiddlewareLetsTheRequestPassWhenNotLocked()
    {
        $this->registerProtectedRoute();

        $response = $this->actingAs($this->makeUser())->get('/protected-page');

        $response->assertOk();
        $response->assertSee('protected content');
    }

    public function testTheMiddlewareLetsTheLockscreenEndpointsPass()
    {
        // The middleware is pushed into the whole 'web' group, so it also runs
        // on the lockscreen endpoints of the package.

        $this->app['router']->pushMiddlewareToGroup('web', RedirectIfLocked::class);

        $response = $this->actingAs($this->makeUser())
            ->withSession([$this->sessionKey => true])
            ->get(route('adminlte.lockscreen.show'));

        $response->assertOk();

        $response = $this->actingAs($this->makeUser())
            ->withSession([$this->sessionKey => true])
            ->post(route('adminlte.lockscreen.unlock'), [
                'password' => $this->password,
            ]);

        $response->assertSessionMissing($this->sessionKey);
        $response->assertSessionHasNoErrors();
    }

    public function testTheMiddlewareLetsTheLogoutRequestPass()
    {
        config(['adminlte.logout_url' => 'logout']);

        Route::middleware(['web', RedirectIfLocked::class])
            ->post('/logout', function () {
                return 'bye';
            });

        $response = $this->actingAs($this->makeUser())
            ->withSession([$this->sessionKey => true])
            ->post('/logout');

        $response->assertOk();
        $response->assertSee('bye');
    }

    public function testTheMiddlewareHonorsTheExtraExcludedPaths()
    {
        config(['adminlte.lockscreen.except' => ['api/*']]);

        Route::middleware(['web', RedirectIfLocked::class])
            ->get('/api/ping', function () {
                return 'pong';
            });

        $response = $this->actingAs($this->makeUser())
            ->withSession([$this->sessionKey => true])
            ->get('/api/ping');

        $response->assertOk();
        $response->assertSee('pong');
    }

    public function testTheMiddlewareAnswersTheJsonRequestsWithTheLockedStatus()
    {
        $this->registerProtectedRoute();

        $response = $this->actingAs($this->makeUser())
            ->withSession([$this->sessionKey => true])
            ->getJson('/protected-page');

        $response->assertStatus(423);
        $response->assertSessionMissing('url.intended');
    }

    /**
     * Register a dummy route protected by the lockscreen middleware.
     *
     * @return void
     */
    protected function registerProtectedRoute()
    {
        Route::middleware(['web', RedirectIfLocked::class])
            ->get('/protected-page', function () {
                return 'protected content';
            });
    }
}

class LockscreenTestUser implements Authenticatable
{
    /**
     * The name of the dummy user.
     *
     * @var string
     */
    public $name = 'Jane Doe';

    /**
     * The email of the dummy user.
     *
     * @var string
     */
    public $email = 'jane.doe@example.com';

    /**
     * The hashed password of the dummy user.
     *
     * @var string
     */
    protected $password;

    /**
     * Create a new dummy user instance.
     *
     * @param  string  $password  The hashed password of the user
     */
    public function __construct($password)
    {
        $this->password = $password;
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
        return $this->password;
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
