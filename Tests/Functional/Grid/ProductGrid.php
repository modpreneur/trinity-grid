<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Tests\Functional\Grid;

use Trinity\Bundle\GridBundle\Grid\BaseGrid;
use Trinity\Bundle\GridBundle\Grid\GridConfigurationBuilder;

/**
 * Class ProductGrid
 * @package Trinity\Tests\Functional
 */
class ProductGrid extends BaseGrid
{
    /**
     * Set up grid (template)
     *
     * @return void
     */
    protected function setUp()
    {
        $this->addTemplate('productGrid.html.twig');
    }

    /**
     * Build grid
     *
     * @param GridConfigurationBuilder $builder
     *
     * @return void
     */
    protected function build(GridConfigurationBuilder $builder)
    {
    }
}
