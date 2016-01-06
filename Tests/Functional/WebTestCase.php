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

        return 'Symfony\Bundle\SecurityBundle\Tests\Functional\app\AppKernel';
    }


    protected static function createKernel(array $options = array())
    {
        $class = self::getKernelClass();
        if (!isset($options['test_case'])) {
            throw new \InvalidArgumentException('The option "test_case" must be set.');
        }

        return new $class(
            $options['test_case'],
            isset($options['root_config']) ? $options['root_config'] : 'config.yml',
            isset($options['environment']) ? $options['environment'] : 'securitybundletest'.strtolower(
                    $options['test_case']
                ),
            isset($options['debug']) ? $options['debug'] : true
        );
    }
}