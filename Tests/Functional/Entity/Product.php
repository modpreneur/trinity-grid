<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Tests\Functional\Entity;


use Trinity\FrameworkBundle\Entity\BaseProduct;


/**
 * Class Product
 * @package Trinity\Bundle\GridBundle\Tests\Functional\Entity
 */
class Product extends BaseProduct
{

    protected $id;


    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->id = 1;
    }


}