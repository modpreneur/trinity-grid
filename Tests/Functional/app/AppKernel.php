<?php

namespace Trinity\Bundle\GridBundle\Tests\Functional\app;

// get the autoload file
$dir = __DIR__;
$lastDir = null;

while ($dir !== $lastDir) {
    $lastDir = $dir;
    if (file_exists($dir.'/autoload.php')) {
        $loader = require$dir.'/autoload.php';
        break;
    }
    if (file_exists($dir.'/autoload.php.dist')) {
        $loader = require $dir.'/autoload.php.dist';
        break;
    }
    if (file_exists($dir.'/vendor/autoload.php')) {
        $loader = require $dir.'/vendor/autoload.php';
        break;
    }
    $dir = dirname($dir);
}


\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

use FOS\RestBundle\FOSRestBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;


/**
 * Class AppKernel.
 */
class AppKernel extends Kernel
{

    /**
     * @return array
     */
    public function registerBundles()
    {
        return array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \JMS\SerializerBundle\JMSSerializerBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new FOSRestBundle(),
            
            new \Trinity\Bundle\LoggerBundle\LoggerBundle(),
            new \Trinity\Bundle\SearchBundle\SearchBundle(),
            new \Trinity\Bundle\SettingsBundle\SettingsBundle(),
            new MonologBundle(),

            new \Trinity\Bundle\GridBundle\GridBundle(),
        );
    }


    /**
     * @param LoaderInterface $loader
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');
        $loader->load(__DIR__.'/config/services.yml');
    }


    /**
     * @return string
     */
    public function getCacheDir()
    {
        return __DIR__.'/./cache';
    }


    /**
     * @return string
     */
    public function getLogDir()
    {
        return __DIR__.'/./logs';
    }
}