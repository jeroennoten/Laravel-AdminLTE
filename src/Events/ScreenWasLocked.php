<?php

namespace JeroenNoten\LaravelAdminLte\Events;

use JeroenNoten\LaravelAdminLte\Http\Controllers\LockscreenController;

class ScreenWasLocked
{
    /**
     * An instance of the lockscreen controller.
     *
     * @var LockscreenController
     */
    public $lockscreen;

    /**
     * The user whose screen was locked.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  LockscreenController  $ctrl
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $user
     */
    public function __construct(LockscreenController $ctrl, $user = null)
    {
        $this->lockscreen = $ctrl;
        $this->user = $user;
    }
}
