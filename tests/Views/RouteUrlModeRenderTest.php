<?php

use Illuminate\Routing\Route as RouteDefinition;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;

/**
 * Checks the panel keeps rendering when the 'use_route_url' option is enabled
 * on an application that misses some of the configured routes. Every one of
 * these views used to abort with a 'RouteNotFoundException'.
 */
class RouteUrlModeRenderTest extends TestCase
{
    /**
     * The authentication views that resolve a configured url.
     *
     * @var array
     */
    protected $authViews = [
        'login', 'register', 'passwords.email',
    ];

    public function setUp(): void
    {
        parent::setUp();

        View::share('errors', new ViewErrorBag());
        config(['adminlte.use_route_url' => true]);
    }

    /**
     * Renders a view of the package and returns the resulting html.
     *
     * @param  string  $view  The name of the view
     * @param  array  $data  The data of the view
     * @return string
     */
    protected function render($view, $data = [])
    {
        View::flushSections();

        return View::make($view, $data)->render();
    }

    public function testThePageRendersWithoutTheConfiguredRoutes()
    {
        $html = $this->render('adminlte::page');

        // The dashboard url degrades to a plain url instead of aborting.

        $this->assertStringContainsString('/home', $html);
    }

    public function testTheAuthViewsRenderWithoutTheConfiguredRoutes()
    {
        foreach ($this->authViews as $view) {
            $html = $this->render("adminlte::auth.{$view}");

            $this->assertNotEmpty($html, "Failed on the '{$view}' view");
        }
    }

    public function testTheErrorViewsRenderWithoutTheConfiguredRoutes()
    {
        $html = $this->render('adminlte::errors.404');

        $this->assertStringContainsString('/home', $html);
    }

    public function testTheConfiguredRoutesAreUsedWhenAvailable()
    {
        Route::getRoutes()->add(
            new RouteDefinition('GET', 'my-dashboard', ['as' => 'home'])
        );

        $html = $this->render('adminlte::page');

        $this->assertStringContainsString('/my-dashboard', $html);
    }
}
