<?php
/**
 * This file is part of Trinity package.
 */


namespace Trinity\Bundle\GridBundle\Filter;


/**
 * Class DateTimeFilter
 * @package Trinity\Bundle\GridBundle\Filter
 */
class DateTimeFilter extends BaseFilter
{

    const GLOBAL_FORMAT = "m/d/Y H:i";

    /**
     * @var string
     */
    protected $name = "dateTime";


    /**
     * @param \Datetime $input
     * @param array $arguments
     * @return string
     */
    function process($input, array $arguments = []) : string
    {
        if(is_null($input)) {
            return "";
        }

        if($input instanceof \DateTime){
            return $input->format(self::GLOBAL_FORMAT);
        }

        throw new \BadFunctionCallException("Argument must be instance of \\DateTime.");
    }

}