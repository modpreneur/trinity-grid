<?php

namespace Trinity\Bundle\GridBundle\Tests\Functional;



use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel;
use Trinity\Bundle\GridBundle\Tests\TestCase;


class WebTestCase extends TestCase
{
    public static function assertRedirect($response, $location)
    {
        self::assertTrue(
            $response->isRedirect(),
            'Response is not a redirect, got status code: '.substr($response, 0, 2000)
        );
        self::assertEquals('http://localhost'.$location, $response->headers->get('Location'));
    }


    protected static function deleteTmpDir($testCase)
    {
        if (!file_exists($dir = sys_get_temp_dir().'/'.Kernel::VERSION.'/'.$testCase)) {
            return;
        }
        $fs = new Filesystem();
        $fs->remove($dir);
    }


    protected static function getKernelClass()
    {
        require_once __DIR__.'/app/AppKernel.php';

        return 'Trinity\Bundle\GridBundle\Tests\Functional\app\AppKernel';
    }


    protected static function createKernel(array $options = array())
    {
        $class = self::getKernelClass();

        return new $class(
            'test',
            isset($options['debug']) ? $options['debug'] : true
        );
    }
}