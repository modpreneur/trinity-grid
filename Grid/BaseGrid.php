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


    /** @var  array */
    protected $format;


    /**
     * BaseGrid constructor.
     */
    public function __construct()
    {
        $this->layout = "GridBundle::default_grid_layout.html.twig";
        $this->templates = [];
        $this->format = [];

        $this->defaultSetUp();
        $this->setUp();
    }


    /**
     * Set default values for basic column. (id, createAt, updateAt)
     */
    public function defaultSetUp()
    {
        $this->addFormat('id', '?.');
        $this->addFormat('createAt', 'm/d/Y H:i');
        $this->addFormat('updateAt', 'm/d/Y H:i');
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


    /**
     * @param string $column
     * @param string $format
     *
     * @return BaseGrid
     */
    public function addFormat($column, $format) : BaseGrid
    {
        $this->format[$column] = $format;

        return $this;
    }


    /**
     * @param  string $column
     * @return string
     */
    public function getFormat($column) : string
    {
        if( array_key_exists($column, $this->format))
            return $this->format[$column];

        return "";
    }


}