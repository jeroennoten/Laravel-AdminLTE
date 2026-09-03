<?php

use Illuminate\Support\Facades\Route;
use JeroenNoten\LaravelAdminLte\Http\Controllers\LockscreenController;

/*
|--------------------------------------------------------------------------
| Lockscreen Routes
|--------------------------------------------------------------------------
|
| These routes are only registered when the lockscreen is enabled on the
| package configuration. The check lives on the service provider, so this file
| carries no condition of its own. Enabling the option on a deployment that
| caches its routes needs a new 'route:cache' run.
|
*/

Route::post('/lockscreen/lock', [LockscreenController::class, 'lock'])
    ->name('lockscreen.lock');

Route::get('/lockscreen', [LockscreenController::class, 'show'])
    ->name('lockscreen.show');

Route::post('/lockscreen/unlock', [LockscreenController::class, 'unlock'])
    ->name('lockscreen.unlock');
