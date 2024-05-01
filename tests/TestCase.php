<?php

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

class TestCase extends BaseTestCase
{
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
    protected function makeMenuBuilder()
    {
        return new Builder([
            new GateFilter(),
            new HrefFilter(),
            new ActiveFilter($this->makeActiveChecker()),
            new ClassesFilter(),
            new DataFilter(),
            new LangFilter(),
            new SearchFilter(),
        ]);
    }

    /**
     * Make an ActiveChecker instance.
     *
     * @return ActiveChecker
     */
    protected function makeActiveChecker()
    {
        return new ActiveChecker();
    }

    /**
     * Make an AdminLte instance.
     *
     * @return AdminLte
     */
    protected function makeAdminLte($filters = [])
    {
        return new AdminLte($filters);
    }
}
