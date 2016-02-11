<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Tests\Functional;

use Nette\Utils\DateTime;
use Trinity\Bundle\GridBundle\Tests\Functional\Entity\Product;


/**
 * Class GridManagerTest
 * @package Trinity\Bundle\GridBundle\Tests\Functional
 */
class GridManagerTest extends WebTestCase
{

    public function testAddGrid()
    {

        $kernel = $this->createClient()->getKernel();

        $container = $kernel->getContainer();
        $loader = $container->get('trinity.grid.manager');

        foreach ($loader->getGrids() as $grid) {
            $this->assertInstanceOf("Trinity\\Bundle\\GridBundle\\Grid\\BaseGrid", $grid);
        }

        $this->assertInstanceOf(
            "Trinity\\Bundle\\GridBundle\\Tests\\Functional\\Grid\\ProductGrid",
            $loader->getGrid('product')
        );
    }


    public function testGetGridNameFromEntities()
    {

        $kernel = $this->createClient()->getKernel();

        $container = $kernel->getContainer();
        $manager = $container->get('trinity.grid.manager');

        $this->assertEquals('product', $manager->getGridNameFromEntities($this->getEntitiesArray()));
    }


    public function testConvert()
    {

        $kernel = $this->createClient()->getKernel();

        $container = $kernel->getContainer();
        $manager = $container->get('trinity.grid.manager');

        $array = $manager->convertEntitiesToArray(
            $this->get('trinity.search'),
            $this->getEntitiesArray(),
            ['id', 'name', 'description', 'nonexistentColumn', 'createdAt']
        );


        $this->assertEquals(
            [
                [
                    'id' => '1.', // global
                    'name' => 'Template edit - John Dee', // template
                    'description' => 'Description.',
                    'nonexistentColumn' => '',
                    'createdAt' => '01/01/2010 00:00',
                ],
            ],
            $array
        );
    }


    protected function getEntitiesArray() : array
    {
        $productA = new Product();

        $productA->setName("John Dee");
        $productA->setDescription("Description.");
        $productA->setCreatedAt(
            DateTime::from("2010-1-1")
        );
        $productA->setUpdatedAt(DateTime::from("2010-1-1"));

        return [
            $productA,
        ];
    }
}