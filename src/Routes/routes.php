<?php

Route::get('adminlte/skins/{skin}', function ($skin) {
    if (config('adminlte.skin_mode') == 'user') {
        $user = Auth::user();
        $user->skin = in_array($skin, ['blue', 'red', 'black', 'purple', 'yellow', 'green']) ? $skin : $user->skin;
        $user->skin_light = in_array($skin, ['dark', 'light']) ? ($skin == 'light') ? true : false : $user->skin_light;
        $user->save();
    }

    return redirect()->back();
})->name('adminlteskinchange')->middleware(['web', 'auth']);
