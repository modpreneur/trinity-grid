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

    /** @var   string[] */
    protected $columnFormat;

    /**
     * BaseGrid constructor.
     */
    public function __construct()
    {
        $this->templates = [];
        $this->format = [];
        $this->columnFormat = [];

        $this->defaultSetUp();
        $this->setUp();
    }


    /**
     * Set default values for basic column. (id, createAt, updateAt)
     */
    protected function defaultSetUp()
    {
        $this->setColumnFilter('id', 'id');
        $this->setColumnFilter('createdAt', 'dateTime');
        $this->setColumnFilter('updatedAt', 'dateTime');
    }


    /**
     * Set up grid (template)
     *
     * @return void
     */
    abstract protected function setUp();


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
     * @param $column
     * @return string|null
     */
    public function getColumnFormat($column) : string
    {
        if (array_key_exists($column, $this->columnFormat)) {
            return $this->columnFormat[$column];
        }

        return '';
    }


    /**
     * @param string $column
     * @param string $format
     */
    public function setColumnFilter($column, $format)
    {
        $this->columnFormat[$column] = $format;
    }
}
