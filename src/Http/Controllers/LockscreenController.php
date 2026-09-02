<?php

namespace JeroenNoten\LaravelAdminLte\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use JeroenNoten\LaravelAdminLte\Events\ScreenWasLocked;
use JeroenNoten\LaravelAdminLte\Events\ScreenWasUnlocked;

class LockscreenController extends Controller
{
    /**
     * The key to use for flag the locked screen on the session.
     *
     * @var string
     */
    protected $sessionKey = 'adminlte_lockscreen';

    /**
     * The prefix to use for the rate limiter keys of the unlock attempts.
     *
     * @var string
     */
    protected $throttlePrefix = 'adminlte-lockscreen';

    /**
     * Lock the screen of the current user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function lock()
    {
        $user = $this->user();

        if (! $user) {
            return redirect()->to($this->loginUrl());
        }

        // Remember the page the user comes from, so the unlock action may
        // return to it. Note the password is never part of this data.

        $previous = url()->previous();

        if ($previous && ! $this->isLockscreenUrl($previous)) {
            session(['url.intended' => $previous]);
        }

        $this->lockScreen();

        event(new ScreenWasLocked($this, $user));

        return redirect()->to($this->lockscreenUrl());
    }

    /**
     * Show the lockscreen page of the current user.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show()
    {
        $user = $this->user();

        if (! $user) {
            return redirect()->to($this->loginUrl());
        }

        if (! $this->isLocked()) {
            return redirect()->intended($this->dashboardUrl());
        }

        return view('adminlte::auth.lockscreen', [
            'user' => $user,
            'unlockUrl' => $this->unlockUrl(),
            'loginUrl' => $this->loginUrl(),
            'dashboardUrl' => $this->dashboardUrl(),
        ]);
    }

    /**
     * Unlock the screen of the current user.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws ValidationException
     */
    public function unlock(Request $request)
    {
        $user = $this->user();

        if (! $user) {
            return redirect()->to($this->loginUrl());
        }

        $request->validate(['password' => ['required', 'string']]);

        $this->ensureIsNotRateLimited($request);

        if (! $this->hasValidPassword($request->input('password'))) {
            RateLimiter::hit($this->throttleKey($request), $this->throttleDecaySeconds());

            throw ValidationException::withMessages([
                'password' => __('adminlte::adminlte.lockscreen_wrong_password'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));

        $this->unlockScreen();

        event(new ScreenWasUnlocked($this, $user));

        return redirect()->intended($this->dashboardUrl());
    }

    /**
     * Check if the screen of the current user is locked or not.
     *
     * @return bool
     */
    public function isLocked()
    {
        return (bool) session($this->sessionKey, false);
    }

    /**
     * Flag the screen of the current user as locked.
     *
     * @return void
     */
    public function lockScreen()
    {
        session([$this->sessionKey => true]);
    }

    /**
     * Flag the screen of the current user as unlocked.
     *
     * @return void
     */
    public function unlockScreen()
    {
        session()->forget($this->sessionKey);
    }

    /**
     * Get the authenticated user of the configured guard.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        return $this->guard()->user();
    }

    /**
     * Get the url of the lockscreen page.
     *
     * @return string
     */
    public function lockscreenUrl()
    {
        return Route::has('adminlte.lockscreen.show')
            ? route('adminlte.lockscreen.show')
            : url('adminlte/lockscreen');
    }

    /**
     * Get the url where the unlock attempts are sent.
     *
     * @return string
     */
    public function unlockUrl()
    {
        return Route::has('adminlte.lockscreen.unlock')
            ? route('adminlte.lockscreen.unlock')
            : url('adminlte/lockscreen/unlock');
    }

    /**
     * Get the guard used to resolve and to verify the current user.
     *
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard(): \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
    {
        return Auth::guard(config('adminlte.lockscreen.guard'));
    }

    /**
     * Check if the given plain text password belongs to the current user. The
     * verification is delegated to the user provider of the configured guard,
     * so any custom hasher of the application is honored. The password is
     * never logged and never stored on the session.
     *
     * @param  string|null  $password
     * @return bool
     */
    protected function hasValidPassword($password): bool
    {
        $user = $this->user();

        if (! $user || ! is_string($password) || $password === '') {
            return false;
        }

        $guard = $this->guard();
        $provider = method_exists($guard, 'getProvider') ? $guard->getProvider() : null;

        if ($provider) {
            return $provider->validateCredentials($user, ['password' => $password]);
        }

        return Hash::check($password, (string) $user->getAuthPassword());
    }

    /**
     * Ensure the unlock attempts of the current user are not rate limited.
     *
     * @param  Request  $request
     * @return void
     *
     * @throws ValidationException
     */
    protected function ensureIsNotRateLimited(Request $request): void
    {
        $maxAttempts = $this->throttleMaxAttempts();

        if ($maxAttempts <= 0) {
            return;
        }

        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), $maxAttempts)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'password' => __('adminlte::adminlte.lockscreen_throttle', [
                'seconds' => $seconds,
                'minutes' => (int) ceil($seconds / 60),
            ]),
        ])->status(429);
    }

    /**
     * Get the rate limiter key of the unlock attempts of the current user.
     *
     * @param  Request  $request
     * @return string
     */
    protected function throttleKey(Request $request): string
    {
        $user = $this->user();
        $id = $user ? $user->getAuthIdentifier() : 'guest';

        return $this->throttlePrefix.'|'.$id.'|'.$request->ip();
    }

    /**
     * Get the amount of unlock attempts allowed on the decay period. A value
     * lower than one disables the throttling.
     *
     * @return int
     */
    protected function throttleMaxAttempts(): int
    {
        return (int) config('adminlte.lockscreen.throttle.max_attempts', 5);
    }

    /**
     * Get the amount of seconds the unlock attempts are counted on.
     *
     * @return int
     */
    protected function throttleDecaySeconds(): int
    {
        return (int) config('adminlte.lockscreen.throttle.decay_seconds', 60);
    }

    /**
     * Check if the given url points to the lockscreen page.
     *
     * @param  string  $url
     * @return bool
     */
    protected function isLockscreenUrl($url): bool
    {
        $path = static function ($value) {
            return trim((string) parse_url($value, PHP_URL_PATH), '/');
        };

        return in_array(
            $path($url),
            [$path($this->lockscreenUrl()), $path($this->unlockUrl())],
            true
        );
    }

    /**
     * Get the url of the login page of the application.
     *
     * @return string
     */
    protected function loginUrl(): string
    {
        return $this->resolveUrl(config('adminlte.login_url', 'login'));
    }

    /**
     * Get the url of the dashboard page of the application.
     *
     * @return string
     */
    protected function dashboardUrl(): string
    {
        return $this->resolveUrl(config('adminlte.dashboard_url', 'home'));
    }

    /**
     * Resolve a configured url, that may be a route name or a plain path.
     *
     * @param  string|null  $url
     * @return string
     */
    protected function resolveUrl($url): string
    {
        if (! $url) {
            return url('/');
        }

        return config('adminlte.use_route_url', false) ? route($url) : url($url);
    }
}
