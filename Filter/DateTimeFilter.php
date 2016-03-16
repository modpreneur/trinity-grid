<?php
/**
 * This file is part of Trinity package.
 */


namespace Trinity\Bundle\GridBundle\Filter;

use Trinity\Bundle\SettingsBundle\Manager\SettingsManagerInterface;



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
     * @var SettingsManagerInterface
     */
    protected $settingsManager;

    /**
     * DateTimeFilter constructor.
     * @param SettingsManagerInterface $settingsManager
     */
    function __construct(SettingsManagerInterface $settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }

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

            if ( $this->settingsManager->has('datetime') ) {
                return $input->format($this->settingsManager->get('datetime'));
            }

            return $input->format(self::GLOBAL_FORMAT);
        }

        if(ctype_digit((string)$input)){

            return date(self::GLOBAL_FORMAT, $input);
        }

        throw new \BadFunctionCallException("Argument must be instance of \\DateTime or Integer (timestamp).");
    }

}