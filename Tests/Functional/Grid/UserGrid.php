<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Tests\Functional\Grid;

use Trinity\Bundle\GridBundle\Grid\BaseGrid;


/**
 * Class UserGrid
 * @package Trinity\Bundle\GridBundle\Tests\Functional\Grid
 */
class UserGrid extends BaseGrid
{

    protected function setUp()
    {
        $this->addTemplate("UserGrid.html.twig");
    }

}