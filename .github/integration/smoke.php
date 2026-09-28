<?php

/*
|--------------------------------------------------------------------------
| Integration Smoke Check
|--------------------------------------------------------------------------
|
| Boots a real Laravel application that required the package, and renders a
| page through the HTTP kernel. The package test suite runs on a testbench
| application, so this is the only check that proves the package still
| installs and renders on the application skeleton the users start from.
|
| Run it from the root of the application: php smoke.php
|
*/

require __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    Illuminate\Http\Request::create('/adminlte-smoke', 'GET')
);

$status = $response->getStatusCode();
$body = (string) $response->getContent();

echo "status: {$status}".PHP_EOL;
echo 'length: '.strlen($body).PHP_EOL;

// The tokens that prove the layout, the asset resolution and the blade
// components all took part on the rendered page.

$expected = [
    'app-wrapper',
    'adminlte.min.css',
    'card-primary',
    'name="smoke"',
];

$missing = [];

foreach ($expected as $token) {
    if (! str_contains($body, $token)) {
        $missing[] = $token;
    }
}

if ($status !== 200 || ! empty($missing)) {
    echo 'missing tokens: '.implode(', ', $missing).PHP_EOL;
    echo substr($body, 0, 4000).PHP_EOL;

    exit(1);
}

echo 'The AdminLTE page was rendered by the application.'.PHP_EOL;
