<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Filter;


/**
 * Interface GridFIlterInterface
 * @package Trinity\Bundle\GridBundle\Filter
 */
interface GridFilterInterface
{

    /**
     * @param string|object|int|bool $input
     * @param array $arguments
     * @return string
     */
    function process($input, array $arguments = []) : string ;


    /**
     * @param string $name
     * @return void
     */
    function setName($name) ;


    /**
     * @return string
     */
    function getName() : string;


    /**
     * @return bool
     */
    function isGlobal() :bool;

}