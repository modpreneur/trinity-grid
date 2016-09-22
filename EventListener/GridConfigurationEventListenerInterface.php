<?php

namespace Trinity\Bundle\GridBundle\EventListener;

use Trinity\Bundle\GridBundle\Event\ConfigurationEvent;

/**
 * Class GridConfigurationEventListenerInterface
 * @package Trinity\Bundle\GridBundle\EventListener
 */
interface GridConfigurationEventListenerInterface
{
    /**
     * @param ConfigurationEvent $event
     * @return void
     */
    public function getConfiguration(ConfigurationEvent $event);
}