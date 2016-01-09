<?php
require_once __DIR__.'/../app/AppKernel.php';

use Trinity\Bundle\GridBundle\Tests\Functional\app\AppKernel;
use Symfony\Component\HttpFoundation\Request;

$kernel = new AppKernel('dev', true);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();