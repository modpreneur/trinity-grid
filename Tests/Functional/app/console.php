<?php
/*
 * This file is part of the Trinity project.
 */
set_time_limit(0);
require_once __DIR__.'/AppKernel.php';
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Debug\Debug;


$input = new ArgvInput();
$env = $input->getParameterOption(array('--env', '-e'), getenv('SYMFONY_ENV') ?: 'dev');
$debug = getenv('SYMFONY_DEBUG') !== '0' && !$input->hasParameterOption(array('--no-debug', '')) && $env !== 'prod';
if ($debug) {
    Debug::enable();
}

use Trinity\Bundle\GridBundle\Tests\Functional\app\AppKernel;

$kernel = new AppKernel($env, $debug);
$application = new Application($kernel);
$application->run($input);

