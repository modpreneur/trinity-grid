<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Grid;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Trinity\Bundle\GridBundle\Service\GridConfigurationService;
use Trinity\Bundle\SearchBundle\NQL\NQLQuery;
use Trinity\Bundle\SettingsBundle\Exception\PropertyNotExistsException;
use Trinity\Bundle\SettingsBundle\Manager\SettingsManager;

/**
 * Class BaseGrid
 * @package Trinity\Grid
 */
abstract class BaseGrid
{
    /** @var  string[] */
    protected $templates;

    /** @var   string[] */
    protected $columnFormat;

    /** @var  int|null */
    protected $limit;

    /** @var  int */
    protected $count = 0;

    /** @var  GridConfigurationService */
    protected $configurationService;

    /** @var  SettingsManager */
    protected $settings;

    /** @var  boolean */
    protected $editable = false;

    /** @var  string */
    protected $orderBy = 'id:ASC';

    /** @var  string */
    protected $url;

    /** @var Router */
    protected $router;

    /**
     * BaseGrid constructor.
     *
     * @param GridConfigurationService $configService
     * @param SettingsManager $settings
     * @param Router $router
     */
    public function __construct(GridConfigurationService $configService, SettingsManager $settings, Router $router)
    {
        $this->configurationService = $configService;
        $this->settings = $settings;
        $this->router = $router;

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


    /**
     * @param NQLQuery $query
     */
    public function prepareQuery(NQLQuery $query)
    {
    }


    /**
     * Build grid
     *
     * @param GridConfigurationBuilder $builder
     *
     * @return void
     */
    abstract protected function build(GridConfigurationBuilder $builder);

    /**
     * Get JSON for grid configuration
     *
     * @return string
     */
    public function getJSON()
    {
        try {
            $limit = $this->limit ?? $this->settings->get('items_on_page') ?? 0;
        } catch (PropertyNotExistsException $e) {
            $limit = 1;
        }

        $entityName = substr(static::class, strrpos(static::class, '\\') + 1, -strlen('Grid'));

        try {
            $url = $this->router->generate('grid_default', ['entity' => $entityName]);
        } catch (InvalidParameterException | RouteNotFoundException | MissingMandatoryParametersException $e) {
            $url = '';
        }

        $gridConfBuilder = $this->configurationService->createGridConfigurationBuilder(
            $url,
            $this->count,
            $limit,
            $this->editable,
            $this->orderBy
        );

        $this->build($gridConfBuilder);

        return $gridConfBuilder->getJSON();
    }

    /**
     * @return int
     */
    public function getLimit() : ?int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getCount() : int
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count)
    {
        $this->count = $count;
    }

    /**
     * @return boolean
     */
    public function isEditable() : bool
    {
        return $this->editable;
    }

    /**
     * @param boolean $editable
     */
    public function setEditable(bool $editable)
    {
        $this->editable = $editable;
    }

    /**
     * @return string
     */
    public function getOrderBy() : string
    {
        return $this->orderBy;
    }

    /**
     * @param string $orderBy
     */
    public function setOrderBy(string $orderBy)
    {
        $this->orderBy = $orderBy;
    }

    /**
     * @return string
     */
    public function getUrl() : ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }
}
