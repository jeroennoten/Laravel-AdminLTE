@extends('adminlte::master')

@php
    $dashboardUrl = View::getSection('dashboard_url')
        ?? config('adminlte.dashboard_url', 'home');

    if (config('adminlte.use_route_url', false)) {
        $dashboardUrl = $dashboardUrl ? route($dashboardUrl) : '';
    } else {
        $dashboardUrl = $dashboardUrl ? url($dashboardUrl) : '';
    }

    $errorCode = $errorCode ?? '500';
    $errorTheme = $errorTheme ?? 'danger';
@endphp

@section('title', $errorTitle ?? $errorCode)

@section('classes_body', 'bg-body-tertiary')

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('body')
    <main class="d-flex align-items-center min-vh-100 py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 text-center">

                    {{-- Error code or icon --}}
                    @hasSection('error_icon')
                        @yield('error_icon')
                    @else
                        <div class="display-1 fw-bold text-{{ $errorTheme }} lh-1 mb-3">
                            {{ $errorCode }}
                        </div>
                    @endif

                    {{-- Error title --}}
                    <h1 class="h3 mb-3">
                        @hasSection('error_title')
                            @yield('error_title')
                        @else
                            {{ $errorTitle ?? '' }}
                        @endif
                    </h1>

                    {{-- Error description --}}
                    <p class="text-secondary mb-4">
                        @hasSection('error_message')
                            @yield('error_message')
                        @else
                            {{ $errorMessage ?? '' }}
                        @endif
                    </p>

                    {{-- Extra content (a search form, a support card, ...) --}}
                    @yield('error_content')

                    {{-- Actions --}}
                    @hasSection('error_actions')
                        @yield('error_actions')
                    @elseif($dashboardUrl)
                        <a href="{{ $dashboardUrl }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
                            {{ __('adminlte::adminlte.back_to_dashboard') }}
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </main>
@stop
