<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Grid;


/**
 * Class BaseGrid
 * @package Trinity\Grid
 */
abstract class BaseGrid
{
    /** @var  string */
    protected $layout;


    /** @var  string[] */
    protected $templates;


    /**
     * BaseGrid constructor.
     */
    public function __construct()
    {
        $this->layout = "TrinityGridBundle::default_grid_layout.html.twig";
        $this->templates = [];
    }


    /**
     * @param string $template
     * @return BaseGrid
     */
    public function addTemplate($template): BaseGrid
    {
        $this->templates[] = $template;

        return $this;
    }


    public function getLayout() : string
    {
        return $this->layout;
    }


    public function getTemplates() : array
    {
        return $this->templates;
    }

}