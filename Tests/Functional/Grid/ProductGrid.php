<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Tests\Functional\Grid;

use Trinity\Bundle\GridBundle\Grid\BaseGrid;


/**
 * Class ProductGrid
 * @package Trinity\Tests\Functional
 */
class ProductGrid extends BaseGrid
{

    protected function setUp()
    {
        $this->addTemplate("ProductGrid.html.twig");
    }

}