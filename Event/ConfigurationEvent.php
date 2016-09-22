<?php

namespace Trinity\Bundle\GridBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Trinity\Bundle\GridBundle\Grid\GridConfigurationBuilder;

/**
 * Class ConfigurationEvent
 * @package Trinity\Bundle\GridBundle\Event
 */
class ConfigurationEvent extends Event
{
    /** @var  GridConfigurationBuilder */
    protected $gridConfigurationBuilder;

    /** @var string */
    protected $sourceName;

    /**
     * ConfigurationEvent constructor.
     * @param string $sourceName
     */
    public function __construct(string $sourceName)
    {
        $this->sourceName = $sourceName;
    }

    /**
     * @return string
     */
    public function getSourceName(): string
    {
        return $this->sourceName;
    }


    /**
     * @return GridConfigurationBuilder
     */
    public function getGridConfigurationBuilder()
    {
        return $this->gridConfigurationBuilder;
    }

    /**
     * @param GridConfigurationBuilder $builder
     */
    public function setGridConfigurationBuilder(GridConfigurationBuilder $builder)
    {
        $this->gridConfigurationBuilder = $builder;
    }


}
