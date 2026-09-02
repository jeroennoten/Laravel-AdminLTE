<?php

use Illuminate\Support\Facades\Route;
use JeroenNoten\LaravelAdminLte\Http\Controllers\LockscreenController;

/*
|--------------------------------------------------------------------------
| Lockscreen Routes
|--------------------------------------------------------------------------
|
| These routes are only registered when the lockscreen is enabled on the
| package configuration. The check lives on the service provider, so the
| 'route:cache' command can not freeze the current value of the option.
|
*/

Route::post('/lockscreen/lock', [LockscreenController::class, 'lock'])
    ->name('lockscreen.lock');

Route::get('/lockscreen', [LockscreenController::class, 'show'])
    ->name('lockscreen.show');

Route::post('/lockscreen/unlock', [LockscreenController::class, 'unlock'])
    ->name('lockscreen.unlock');
