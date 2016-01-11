<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Filter;

/**
 * Class ObjectFilter
 * @package Trinity\Bundle\GridBundle\Filter
 */
class ObjectFilter extends BaseFilter
{
    protected $global = true;


    /**
     * @param string|object|int|bool $input
     * @param array $arguments
     * @return string
     */
    function process($input, array $arguments = []) : string
    {

        if ((is_object($input) && method_exists($input, 'getName'))) {
            $input = $input->getName();
        } elseif (is_object($input)) {
            $input = (string)$input;
        }

        return $input;
    }
}