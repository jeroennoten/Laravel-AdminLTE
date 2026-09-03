<?php

use Illuminate\Routing\Route as RouteDefinition;
use Illuminate\Support\Facades\Route;
use JeroenNoten\LaravelAdminLte\Layout\Navigation;

class NavigationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        url()->forceRootUrl('http://example.com');
    }

    /**
     * Registers a named route on the application router.
     *
     * @param  string  $uri  The uri of the route
     * @param  string  $name  The name of the route
     * @return void
     */
    protected function addNamedRoute($uri, $name)
    {
        Route::getRoutes()->add(
            new RouteDefinition('GET', $uri, ['as' => $name])
        );
    }

    public function testAPlainUrlIsResolved()
    {
        config(['adminlte.use_route_url' => false]);

        $this->assertEquals('http://example.com/home', Navigation::makeUrl('home'));
    }

    public function testARouteNameIsResolvedOnTheRouteMode()
    {
        $this->addNamedRoute('dashboard', 'home');

        config(['adminlte.use_route_url' => true]);

        $this->assertEquals(
            'http://example.com/dashboard',
            Navigation::makeUrl('home')
        );
    }

    public function testAnUnknownRouteFallsBackToAPlainUrl()
    {
        config(['adminlte.use_route_url' => true]);

        $this->assertEquals(
            'http://example.com/home',
            Navigation::makeUrl('home')
        );
    }

    public function testARouteMissingItsParametersFallsBackToAPlainUrl()
    {
        $this->addNamedRoute('users/{id}', 'profile');

        config(['adminlte.use_route_url' => true]);

        $this->assertEquals(
            'http://example.com/profile',
            Navigation::makeUrl('profile')
        );
    }

    public function testAnEmptyTargetResolvesToAnEmptyString()
    {
        foreach ([null, '', false, [], new stdClass()] as $target) {
            $this->assertSame('', Navigation::makeUrl($target));
        }
    }

    public function testTheRouteModeIsReadFromTheConfiguration()
    {
        config(['adminlte.use_route_url' => true]);
        $this->assertTrue(Navigation::isRouteMode());

        config(['adminlte.use_route_url' => false]);
        $this->assertFalse(Navigation::isRouteMode());
    }
}
