<?php

namespace JeroenNoten\LaravelAdminLte\Http\Controllers;

use JeroenNoten\LaravelAdminLte\Events\DarkModeWasToggled;

class DarkModeController extends Controller
{
    /**
     * The key to use for save dark mode status on the session.
     *
     * @var string
     */
    protected $sessionKey = 'adminlte_dark_mode';

    /**
     * Toggle the dark mode status.
     *
     * @return void
     */
    public function toggle()
    {
        // Store the new darkmode status on the session. This way, we can keep
        // the dark mode preference over multiple requests.

        session([$this->sessionKey => ! $this->isEnabled()]);

        // Trigger an event to notify this situation. This way, an user may
        // catch the new dark mode status and update the preference on a
        // database or another tool to persist data.

        event(new DarkModeWasToggled($this));
    }

    /**
     * Check if the dark mode is currently enabled or not.
     *
     * @return bool
     */
    public function isEnabled()
    {
        // First, check if dark mode status was previously saved on the session.

        if (! is_null(session($this->sessionKey, null))) {
            return session($this->sessionKey);
        }

        // Otherwise, fallback to the default package configuration value.

        return (bool) config('adminlte.layout_dark_mode', false);
    }

    /**
     * Enables the dark mode.
     *
     * @return void
     */
    public function enable()
    {
        session([$this->sessionKey => true]);
    }

    /**
     * Disables the dark mode.
     *
     * @return void
     */
    public function disable()
    {
        session([$this->sessionKey => false]);
    }
}
