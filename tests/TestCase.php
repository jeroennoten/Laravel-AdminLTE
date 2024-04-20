<?php

use Illuminate\Auth\Access\Gate;
use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\UrlGenerator;
use JeroenNoten\LaravelAdminLte\AdminLte;
use JeroenNoten\LaravelAdminLte\Menu\ActiveChecker;
use JeroenNoten\LaravelAdminLte\Menu\Builder;
use JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter;
use JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter;
use JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter;
use JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter;
use JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter;
use JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter;
use JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class TestCase extends BaseTestCase
{
    private $routeCollection;

    /**
     * Load the package services providers.
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        // Register our package service provider into the Laravel application.

        return ['JeroenNoten\LaravelAdminLte\AdminLteServiceProvider'];
    }

    /**
     * Make a menu builder instance.
     *
     * @return Builder
     */
    protected function makeMenuBuilder($uri = 'http://example.com', GateContract $gate = null)
    {
        return new Builder([
            new GateFilter($gate ?: $this->makeGate()),
            new HrefFilter($this->makeUrlGenerator($uri)),
            new ActiveFilter($this->makeActiveChecker($uri)),
            new ClassesFilter(),
            new DataFilter(),
            new LangFilter(),
            new SearchFilter(),
        ]);
    }

    protected function makeActiveChecker($uri = 'http://example.com', $scheme = null)
    {
        return new ActiveChecker($this->makeUrlGenerator($uri, $scheme));
    }

    private function makeRequest($uri)
    {
        return Request::createFromBase(SymfonyRequest::create($uri));
    }

    protected function makeAdminLte($filters = [])
    {
        return new AdminLte($filters);
    }

    protected function makeUrlGenerator($uri = 'http://example.com', $scheme = null)
    {
        $UrlGenerator = new UrlGenerator(
            $this->getRouteCollection(),
            $this->makeRequest($uri)
        );

        if ($scheme) {
            $UrlGenerator->forceScheme($scheme);
        }

        return $UrlGenerator;
    }

    protected function makeGate()
    {
        $userResolver = function () {
            return new GenericUser([]);
        };

        return new Gate($this->app, $userResolver);
    }

    protected function getRouteCollection()
    {
        if (! $this->routeCollection) {
            $this->routeCollection = new RouteCollection();
        }

        return $this->routeCollection;
    }
}
