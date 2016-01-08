<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Tests\Functional;

use Nette\Utils\DateTime;
use Trinity\Bundle\GridBundle\Tests\Functional\Entity\Product;


/**
 * Class GridLoaderTest
 * @package Trinity\Bundle\GridBundle\Tests\Functional
 */
class GridLoaderTest extends WebTestCase
{

    public function testAddGrid(){

        $kernel = $this->createClient()->getKernel();

        $container  = $kernel->getContainer();
        $loader = $container->get('trinity.grid.manager');

        foreach($loader->getGrids() as $grid){
            $this->assertInstanceOf("Trinity\\Bundle\\GridBundle\\Grid\\BaseGrid", $grid);
        }

        $this->assertInstanceOf(
            "Trinity\\Bundle\\GridBundle\\Tests\\Functional\\Grid\\ProductGrid",
            $loader->getGrid('product')
        );
    }

    public function testGetGridNameFromEntities(){

        $kernel = $this->createClient()->getKernel();

        $container  = $kernel->getContainer();
        $manager = $container->get('trinity.grid.manager');

        $this->assertEquals('product', $manager->getGridNameFromEntieies($this->getEntitiesErray()));
    }


    public function testConvert(){

        $kernel = $this->createClient()->getKernel();

        $container  = $kernel->getContainer();
        $manager = $container->get('trinity.grid.manager');

        $array = $manager->convertEntitiesToArray( $this->getEntitiesErray(), ['id', 'name', 'description', 'nonexistentColumn'] );

        $this->assertEquals(
            [
                [
                    'id'   => '1.',
                    'name' => '1. John Dee',
                    'description' => 'Description.',
                    'nonexistentColumn' => ''
                ]
            ],
            $array
        );
    }


    protected function getEntitiesErray() : array {
        $productA = new Product();

        $productA
            ->setName("John Dee")
            ->setDescription("Description.")
            ->setCreatedAt(DateTime::from("2010-1-1"))
            ->setUpdatedAt(DateTime::from("2010-1-1"));

        return [
            $productA
        ];
    }
}