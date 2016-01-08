<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Grid;


/**
 * Class GridManager
 * @package Trinity\Grid
 */
class GridLoader
{
    /**
     * @var array
     */
    protected $grids;


    /**
     * GridLoader constructor.
     */
    public function __construct()
    {
        $this->grids = [];
    }


    /**
     * @param string $alias
     * @param BaseGrid $grid
     *
     * @return GridLoader
     */
    public function addGrid($alias, $grid) : GridLoader{
        $this->grids[$alias] = $grid;

        return $this;
    }


    /**
     * @return array
     */
    public function getGrids() :array
    {
        return $this->grids;
    }


    /**
     * @param String $name
     * @return BaseGrid|null
     */
    public function getGrid($name) : BaseGrid{
        if(array_key_exists($name, $this->grids)){
           return $this->grids[$name];
        }

        return null;
    }

}