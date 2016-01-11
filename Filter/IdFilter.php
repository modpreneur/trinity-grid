<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Filter;

/**
 * Class IdFilter
 * @package Trinity\Bundle\GridBundle\Filter
 */
class IdFilter extends BaseFilter
{

    /**
     * @var string
     */
    protected $name = 'id';


    /**
     * @param string|object|int|bool $input
     * @param array $arguments
     * @return string
     */
    function process($input, array $arguments = []) : string
    {
        return $input.'.';
    }
}