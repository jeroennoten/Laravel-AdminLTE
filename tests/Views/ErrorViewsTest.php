<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use JeroenNoten\LaravelAdminLte\Console\PackageResources\ErrorViewsResource;

class ErrorViewsTest extends TestCase
{
    /**
     * The http status codes the package provides a view for.
     *
     * @var array
     */
    protected $statusCodes = ['401', '403', '404', '419', '429', '500', '503'];

    /**
     * Tear down this testing class.
     */
    public function tearDown(): void
    {
        View::flushSections();

        (new ErrorViewsResource())->uninstall();

        parent::tearDown();
    }

    public function testEveryStatusCodeHasAView()
    {
        foreach ($this->statusCodes as $code) {
            $this->assertTrue(
                View::exists("adminlte::errors.{$code}"),
                "The view of the {$code} error is missing."
            );
        }
    }

    public function testEveryErrorViewRenders()
    {
        foreach ($this->statusCodes as $code) {
            View::flushSections();

            $html = View::make("adminlte::errors.{$code}")->render();

            $this->assertStringContainsString('<!DOCTYPE html>', $html);
            $this->assertStringContainsString('min-vh-100', $html);
            $this->assertStringContainsString(
                __('adminlte::adminlte.back_to_dashboard'),
                $html
            );
        }
    }

    public function testTheErrorCodeAndTheThemeAreRendered()
    {
        $html = View::make('adminlte::errors.404')->render();

        $this->assertStringContainsString('text-primary', $html);
        $this->assertMatchesRegularExpression('/display-1[^>]*>\s*404\s*</', $html);
        $this->assertStringContainsString(
            __('adminlte::adminlte.error_not_found_title'),
            $html
        );

        View::flushSections();

        $html = View::make('adminlte::errors.500')->render();

        $this->assertStringContainsString('text-danger', $html);
        $this->assertMatchesRegularExpression('/display-1[^>]*>\s*500\s*</', $html);
    }

    public function testTheMaintenanceViewShowsAnIconInsteadOfTheCode()
    {
        $html = View::make('adminlte::errors.503')->render();

        $this->assertStringContainsString('bi bi-tools', $html);
        $this->assertStringNotContainsString('display-1 fw-bold text-warning', $html);
    }

    public function testTheErrorViewsAreLocalized()
    {
        app()->setLocale('de');

        $html = View::make('adminlte::errors.404')->render();

        $this->assertStringContainsString(
            __('adminlte::adminlte.error_not_found_title'),
            $html
        );
        $this->assertStringNotContainsString('Oops! Page not found.', $html);

        app()->setLocale('en');
    }

    public function testTheDashboardLinkFollowsTheConfiguration()
    {
        config([
            'adminlte.use_route_url' => false,
            'adminlte.dashboard_url' => 'my-home',
        ]);

        $html = View::make('adminlte::errors.404')->render();

        $this->assertStringContainsString(url('my-home'), $html);
    }

    public function testTheDashboardLinkIsDroppedWhenNotConfigured()
    {
        config(['adminlte.dashboard_url' => '']);

        $html = View::make('adminlte::errors.404')->render();

        $this->assertStringNotContainsString(
            __('adminlte::adminlte.back_to_dashboard'),
            $html
        );
    }

    public function testTheErrorViewsAcceptCustomSections()
    {
        View::startSection('error_title');
        echo 'MY-TITLE';
        View::stopSection();

        View::startSection('error_actions');
        echo '<a href="/x">MY-ACTION</a>';
        View::stopSection();

        $html = View::make('adminlte::errors.404')->render();

        $this->assertStringContainsString('MY-TITLE', $html);
        $this->assertStringContainsString('MY-ACTION', $html);
        $this->assertStringNotContainsString(
            __('adminlte::adminlte.back_to_dashboard'),
            $html
        );
    }

    public function testTheResourcePublishesThinExtendingViews()
    {
        $resource = new ErrorViewsResource();

        $resource->uninstall();
        $this->assertFalse($resource->installed());

        $resource->install();
        $this->assertTrue($resource->installed());
        $this->assertTrue($resource->exists());

        // The published views only extend the package ones, so an update of
        // the package reaches the application without republishing.

        $target = $resource->target.DIRECTORY_SEPARATOR.'404.blade.php';

        $this->assertTrue(File::isFile($target));
        $this->assertEquals("@extends('adminlte::errors.404')", File::get($target));
    }

    public function testTheResourceRemovesEveryPublishedView()
    {
        $resource = new ErrorViewsResource();

        $resource->install();
        $resource->uninstall();

        foreach ($this->statusCodes as $code) {
            $this->assertFalse(
                File::isFile($resource->target.DIRECTORY_SEPARATOR."{$code}.blade.php")
            );
        }
    }
}
