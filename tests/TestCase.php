<?php

use Illuminate\Http\Request;
use Illuminate\Auth\Access\Gate;
use Illuminate\Auth\GenericUser;
use Illuminate\Events\Dispatcher;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Routing\RouteCollection;
use JeroenNoten\LaravelAdminLte\AdminLte;
use JeroenNoten\LaravelAdminLte\Menu\Builder;
use JeroenNoten\LaravelAdminLte\Menu\ActiveChecker;
use JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter;
use JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter;
use JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter;
use JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter;
use JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class TestCase extends PHPUnit_Framework_TestCase
{
    private $dispatcher;

    private $routeCollection;

    protected function makeMenuBuilder($uri = 'http://example.com', GateContract $gate = null, $locale = 'en')
    {
        return new Builder([
            new HrefFilter($this->makeUrlGenerator($uri)),
            new ActiveFilter($this->makeActiveChecker($uri)),
            new SubmenuFilter(),
            new ClassesFilter(),
            new GateFilter($gate ?: $this->makeGate()),
            new LangFilter($this->makeTranslator($locale)),
        ]);
    }

    protected function makeTranslator($locale = 'en')
    {
        $translationLoader = new Illuminate\Translation\FileLoader(new Illuminate\Filesystem\Filesystem, 'resources/lang/');

        return new Illuminate\Translation\Translator($translationLoader, $locale);
    }

    protected function makeActiveChecker($uri = 'http://example.com')
    {
        return new ActiveChecker($this->makeRequest($uri), $this->makeUrlGenerator($uri));
    }

    private function makeRequest($uri)
    {
        return Request::createFromBase(SymfonyRequest::create($uri));
    }

    protected function makeAdminLte()
    {
        return new AdminLte($this->getFilters(), $this->getDispatcher(), $this->makeContainer());
    }

    protected function makeUrlGenerator($uri = 'http://example.com')
    {
        return new UrlGenerator($this->getRouteCollection(), $this->makeRequest($uri));
    }

    protected function makeGate()
    {
        $userResolver = function () {
            return new GenericUser([]);
        };

        return new Gate($this->makeContainer(), $userResolver);
    }

    protected function makeContainer()
    {
        return new Illuminate\Container\Container();
    }

    protected function getDispatcher()
    {
        if (! $this->dispatcher) {
            $this->dispatcher = new Dispatcher;
        }

        return $this->dispatcher;
    }

    private function getFilters()
    {
        return [];
    }

    protected function getRouteCollection()
    {
        if (! $this->routeCollection) {
            $this->routeCollection = new RouteCollection();
        }

        return $this->routeCollection;
    }
}
