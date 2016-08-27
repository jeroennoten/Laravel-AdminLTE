<?php


use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\UrlGenerator;
use JeroenNoten\LaravelAdminLte\AdminLte;
use JeroenNoten\LaravelAdminLte\Menu\ActiveChecker;
use JeroenNoten\LaravelAdminLte\Menu\Builder;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Illuminate\Auth\Access\Gate;


class TestCase extends PHPUnit_Framework_TestCase
{
    private $dispatcher;
    

    protected function makeMenuBuilder($uri = 'http://example.com')
    {
        return new Builder(
            $this->makeUrlGenerator($uri),
            $this->makeActiveChecker($uri)
        );
    }

    protected function makeActiveChecker($uri = 'http://example.com')
    {
        return new ActiveChecker($this->makeRequest($uri));
    }

    private function makeRequest($uri)
    {
        return Request::createFromBase(SymfonyRequest::create($uri));
    }
    
    protected function makeAdminLte(Gate $gate)
    {
        return new AdminLte(
            $this->getDispatcher(),
            $this->makeUrlGenerator(),
            $this->makeActiveChecker(),
            $gate
            
        );
    }

    protected function makeUrlGenerator($uri = 'http://example.com')
    {
        return new UrlGenerator(
            new RouteCollection,
            $this->makeRequest($uri)
        );
    }

    protected function getDispatcher()
    {
        if (!$this->dispatcher) {
            $this->dispatcher = new Dispatcher;
        }

        return $this->dispatcher;
    }
}