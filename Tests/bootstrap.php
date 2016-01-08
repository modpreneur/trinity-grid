<?php
/**
 * This file is part of Trinity package.
 */

$file = __DIR__.'/../vendor/autoload.php';

if (!file_exists($file)) {
    $file = __DIR__.'/../../../../../../vendor/autoload.php';
    if (!file_exists($file)) {
        throw new RuntimeException('Install dependencies to run test suite.');
    }
}



var_dump($file);
$autoload = require_once $file;