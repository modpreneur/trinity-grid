<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Tests\Functional\Grid;

use Trinity\Bundle\GridBundle\Grid\BaseGrid;
use Trinity\Bundle\GridBundle\Grid\GridConfigurationBuilder;
use Trinity\Bundle\SearchBundle\NQL\NQLQuery;

/**
 * Class UserGrid
 * @package Trinity\Bundle\GridBundle\Tests\Functional\Grid
 */
class UserGrid extends BaseGrid
{
    /**
     * Set up grid (template)
     *
     * @return void
     */
    protected function setUp()
    {
        $this->addTemplate('UserGrid.html.twig');
    }

    /**
     * @param NQLQuery $query
     */
    public function prepareQuery(NQLQuery $query)
    {
        $query->getWhere()->replaceColumn('fullName', ['firstName', 'lastName']);
        $query->getOrderBy()->replaceColumn('fullName', ['firstName', 'lastName']);
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
