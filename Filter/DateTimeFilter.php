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

    const GLOBAL_FORMAT = 'm/d/Y H:i';

    /**
     * @var string
     */
    protected $name = 'dateTime';

    /**
     * @var SettingsManagerInterface
     */
    protected $settingsManager;

    /**
     * DateTimeFilter constructor.
     * @param SettingsManagerInterface $settingsManager
     */
    public function __construct(SettingsManagerInterface $settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }

    /**
     * @param \Datetime|int $input
     * @param array $arguments
     * @return string
     * @throws \BadFunctionCallException
     */
    public function process($input, array $arguments = []) : string
    {
        if (null === $input) {
            return '';
        }

        if ($input instanceof \DateTime) {
            if ($this->settingsManager->has('date_time')) {
                return $input->format($this->settingsManager->get('date_time'));
            }

            return $input->format(self::GLOBAL_FORMAT);
        }

        /*
         * Elastic have dates as timestamps
         */
        if (ctype_digit((string)$input)) {
            if ($this->settingsManager->has('date_time')) {
                return date($this->settingsManager->get('date_time'), $input);
            }

            return date(self::GLOBAL_FORMAT, $input);
        }

        throw new \BadFunctionCallException("Argument must be instance of \\DateTime or Integer (timestamp).");
    }
}
