<?php

namespace Trinity\Bundle\GridBundle\Tests\Functional;


use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Trinity\Bundle\GridBundle\DependencyInjection\Compiler\GridLoaderPass;


class GridLoaderTest extends WebTestCase
{
    protected function setUp()
    {
        $kernel = static::createKernel();
        $this->application = new Application($kernel);
        $this->application->doRun(new ArrayInput(array()), new NullOutput());
    }


    private function createContainer()
    {
        $container = new ContainerBuilder(new ParameterBag(array(
            'kernel.cache_dir' => __DIR__,
            'kernel.root_dir' => __DIR__.'/Fixtures',
            'kernel.charset' => 'UTF-8',
            'kernel.debug' => false,
            'kernel.bundles' => array('GridBundle' => 'Trinity\\Bundle\\GridBundle\\GridBundle'),
        )));

        return $container;
    }


    /**
     * @test
     */
    public function testLoad(){
        $cb = new ContainerBuilder();

        $gridLoader = new GridLoaderPass();
        //$gridLoader->process($cb);

        var_dump(1);
    }


    private function compileContainer(ContainerBuilder $container)
    {
        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->compile();
    }


}