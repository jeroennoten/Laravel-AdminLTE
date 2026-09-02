<?php

require __DIR__.'/../vendor/autoload.php';

// The console tests publish the package resources into the testbench
// application, and the configuration file is one of the targets. When a run is
// interrupted, the dummy file those tests create survives, and every later run
// fails while booting: the service provider merges the package configuration
// with the dummy string and raises a type error. So, the leftover is dropped
// before the suite starts.

$dummyConfig = __DIR__.'/../vendor/orchestra/testbench-core/laravel/config/adminlte.php';

if (is_file($dummyConfig) && trim((string) file_get_contents($dummyConfig)) === 'dummy-content') {
    unlink($dummyConfig);
}
