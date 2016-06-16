<?php
/**
 * Created by PhpStorm.
 * User: fisa
 * Date: 2/10/16
 * Time: 11:22 AM
 */

namespace Trinity\Bundle\GridBundle\Grid;

use Trinity\Bundle\GridBundle\Exception\DuplicateColumnException;

/**
 * Class GridConfigurationBuilder
 * @package Necktie\AppBundle\Utils
 */
class GridConfigurationBuilder
{
    private $defaultColumnProperties = [
        'editable' => false,
        'allowOrder' => true,
        'hidden' => false
    ];
    private $configuration = [];

    /**
     * GridConfigurationBuilder constructor.
     *
     * @param string $url
     * @param int $maxEntities
     * @param int $limit
     * @param bool $editable
     */
    public function __construct(string $url, int $maxEntities = 1, int $limit = 15, bool $editable = false)
    {
        $this->configuration['url'] = $url;
        $this->configuration['max'] = $maxEntities;
        $this->configuration['limit'] = $limit;
        $this->configuration['columns'] = [];
        $this->configuration['editable'] = $editable;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setProperty(string $name, string $value)
    {
        $this->configuration[$name] = $value;
    }

    /**
     * @param string $name
     */
    public function removeProperty(string $name)
    {
        if (array_key_exists($name, $this->configuration)) {
            unset($this->configuration[$name]);
        }
    }

    /**
     * @param string $name
     * @param string $label
     * @param array $properties
     *
     * @throws \Trinity\Bundle\GridBundle\Exception\DuplicateColumnException
     */
    public function addColumn(string $name, string $label = '', array $properties = [])
    {
        if ($this->findColumnIndex($name) === -1) {
            foreach ($this->defaultColumnProperties as $key => $value) {
                if (!array_key_exists($key, $properties)) {
                    $properties[$key] = $value;
                }
            }

            $properties['name'] = $name;
            $properties['label'] = $label ?: $name;
            $properties['editable'] = $this->configuration['editable'] ? $properties['editable'] : false;

            $this->configuration['columns'][] = $properties;
        } else {
            throw new DuplicateColumnException("Column with name \"{$name}\" already exists!");
        }
    }

    /**
     * @param string $name
     */
    public function removeColumn(string $name)
    {
        $index = $this->findColumnIndex($name);
        if ($index !== -1) {
            array_splice($this->configuration['columns'], $index, 1);
        }
    }

    /**
     * @param string $name
     *
     * @return int
     */
    private function findColumnIndex($name) : int
    {
        /** @var int $index */
        /** @var array $column */
        foreach ($this->configuration['columns'] as $index => $column) {
            if ($column['name'] === $name) {
                return $index;
            }
        }
        return -1;
    }

    /**
     * @return array
     */
    public function getConfiguration() : array
    {
        return $this->configuration;
    }

    /**
     * @return string
     */
    public function getJSON() : string
    {
        return json_encode($this->configuration);
    }
}
