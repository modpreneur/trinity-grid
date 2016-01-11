<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Filter;


/**
 * Class BaseFilter
 * @package Trinity\Bundle\GridBundle\Grid\Filter
 */
abstract class BaseFilter implements GridFilterInterface
{
    /** @var string */
    protected $name = "";

    /** @var  bool */
    protected $global = false;


    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }


    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * @return boolean
     */
    public function isGlobal() : bool
    {
        return $this->global;
    }


    /**
     * @param boolean $global
     */
    public function setGlobal($global)
    {
        $this->global = $global;
    }

}