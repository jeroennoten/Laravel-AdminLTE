<?php

namespace JeroenNoten\LaravelAdminLte\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use JeroenNoten\LaravelAdminLte\Http\Controllers\LockscreenController;

class RedirectIfLocked
{
    /**
     * An instance of the lockscreen controller.
     *
     * @var LockscreenController
     */
    protected $lockscreen;

    /**
     * Create a new middleware instance.
     *
     * @param  LockscreenController  $lockscreen
     */
    public function __construct(LockscreenController $lockscreen)
    {
        $this->lockscreen = $lockscreen;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $this->lockscreen->isLocked() || $this->shouldPassThrough($request)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => __('adminlte::adminlte.lockscreen_message'),
            ], 423);
        }

        if ($request->isMethod('GET')) {
            session(['url.intended' => $request->fullUrl()]);
        }

        return redirect()->to($this->lockscreen->lockscreenUrl());
    }

    /**
     * Check if the given request has to reach the application even while the
     * screen is locked. The lockscreen endpoints themselves and the logout of
     * the application are always allowed.
     *
     * @param  Request  $request
     * @return bool
     */
    protected function shouldPassThrough(Request $request): bool
    {
        $name = $request->route() ? $request->route()->getName() : null;

        if (is_string($name) && str_starts_with($name, 'adminlte.lockscreen.')) {
            return true;
        }

        foreach ($this->allowedPaths() as $path) {
            if ($path !== '' && $request->is($path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the paths that are allowed while the screen is locked.
     *
     * @return array
     */
    protected function allowedPaths(): array
    {
        $paths = [
            $this->pathOf($this->lockscreen->lockscreenUrl()),
            $this->pathOf($this->lockscreen->unlockUrl()),
            $this->configuredPath(config('adminlte.logout_url', 'logout')),
            $this->configuredPath(config('adminlte.login_url', 'login')),
        ];

        $extra = config('adminlte.lockscreen.except', []);

        if (is_array($extra)) {
            $paths = array_merge($paths, $extra);
        }

        return array_filter(array_map([$this, 'normalizePath'], $paths));
    }

    /**
     * Get the relative path of a configured url setting, that may also be a
     * route name.
     *
     * @param  string|null  $url
     * @return string
     */
    protected function configuredPath($url): string
    {
        if (! $url) {
            return '';
        }

        if (config('adminlte.use_route_url', false)) {
            return Route::has($url)
                ? $this->pathOf(route($url))
                : '';
        }

        return $this->normalizePath($url);
    }

    /**
     * Get the relative path of an absolute url.
     *
     * @param  string  $url
     * @return string
     */
    protected function pathOf($url): string
    {
        return $this->normalizePath(parse_url((string) $url, PHP_URL_PATH));
    }

    /**
     * Normalize a path, so it may be compared with the request path.
     *
     * @param  string|null  $path
     * @return string
     */
    protected function normalizePath($path): string
    {
        return trim((string) $path, '/');
    }
}
