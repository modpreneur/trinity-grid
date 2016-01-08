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
        $this->layout = "GridBundle::default_grid_layout.html.twig";
        $this->templates = [];

        $this->setUp();
    }


    /**
     * Set up grid (template)
     *
     * @return void
     */
    public abstract function setUp();


    /**
     * @param string $template
     * @return BaseGrid
     */
    public function addTemplate($template): BaseGrid
    {
        $this->templates[] = $template;

        return $this;
    }


    /**
     * Return twig layout
     *
     * @return string
     */
    public function getLayout() : string
    {
        return $this->layout;
    }


    /**
     * Return twig templates.
     *
     * @return array
     */
    public function getTemplates() : array
    {
        return $this->templates;
    }

}