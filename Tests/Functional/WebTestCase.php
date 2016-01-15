<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Tests\Functional;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel;
use Trinity\Bundle\GridBundle\Tests\TestCase;


/**
 * Class WebTestCase
 * @package Trinity\Bundle\GridBundle\Tests\Functional
 */
class WebTestCase extends TestCase
{
    /**
     * @var bool
     */
    protected $isInit = false;


    protected function init()
    {

        if ($this->isInit === false) {

            exec('php bin/console.php doctrine:database:drop --force');
            exec('php bin/console.php doctrine:schema:create');
            exec('php bin/console.php doctrine:schema:update');

            $kernel = $this->createClient()->getKernel();
            $container = $kernel->getContainer();
            $em = $container->get('doctrine.orm.default_entity_manager');

            $data = new DataSet();
            $data->load($em);
        }

        $this->isInit = true;
    }


    public function setUp()
    {
        parent::setUp();
        $this->init();
    }


    /**
     * @param string $serviceName
     * @return object
     */
    protected function get($serviceName)
    {
        $kernel = $this->createClient()->getKernel();
        $container = $kernel->getContainer();

        return $container->get($serviceName);
    }


}