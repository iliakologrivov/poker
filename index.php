#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Application;

try {
    $app = new Application($argv);
    $app->run();
} catch (\Exception $exception) {
    echo 'error: ' . $exception->getMessage() . PHP_EOL;
} catch (Throwable $throwable) {
    echo 'error: ' . $exception->getMessage() . PHP_EOL;
}