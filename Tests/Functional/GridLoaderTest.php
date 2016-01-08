<?php

namespace Trinity\Bundle\GridBundle\Tests\Functional;



class GridLoaderTest extends WebTestCase
{

    public function testAddGrid(){

        $kernel = $this->createClient()->getKernel();

        $container  = $kernel->getContainer();
        $loader = $container->get('trinity.grid.loader');

        foreach($loader->getGrids() as $grid){
            $this->assertInstanceOf("Trinity\\Bundle\\GridBundle\\Grid\\BaseGrid", $grid);
        }

        $this->assertInstanceOf("Trinity\\Bundle\\GridBundle\\Tests\\Functional\\ProductGrid", $loader->getGrid('product'));

    }

}