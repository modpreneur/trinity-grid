<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Filter;

/**
 * Interface FilterInterface
 * @package Trinity\Bundle\GridBundle\Filter
 */
interface FilterInterface
{

    /**
     * @param string|object|int|bool $input
     * @param array $arguments
     * @return string
     */
    public function process($input, array $arguments = []);


    /**
     * @param string $name
     * @return void
     */
    public function setName($name);


    /**
     * @return string
     */
    public function getName() : string;


    /**
     * @return bool
     */
    public function isGlobal() :bool;
}
