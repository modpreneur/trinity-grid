<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Grid;


/**
 * Class GridManager
 * @package Trinity\Grid
 */
class GridManager
{
    /**
     * @var array
     */
    protected $grids;

    /** @var  \Twig_Environment */
    protected $twig;



    /**
     * GridManager constructor.
     */
    public function __construct($e)
    {
        $this->twig = new \Twig_Environment($e);
        $this->grids = [];
    }


    /**
     * @param string $alias
     * @param BaseGrid $grid
     *
     * @return GridManager
     */
    public function addGrid($alias, $grid) : GridManager{
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