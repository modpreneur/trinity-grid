<?php
/**
 * Created by PhpStorm.
 * User: rockuo
 * Date: 21.6.16
 * Time: 9:04
 */

namespace Trinity\Bundle\GridBundle\Service;

use Trinity\Bundle\GridBundle\Grid\GridConfigurationBuilder;
use Trinity\Bundle\SettingsBundle\Manager\SettingsManager;

/**
 * Class GridConfigurationService
 * @package Necktie\AppBundle\Service
 */
class GridConfigurationService
{
    /** @var  SettingsManager $settingManager */
    private $settingManager;

    /**
     * GridConfigurationService constructor.
     * @param SettingsManager $settingsManager
     */
    public function __construct(SettingsManager $settingsManager)
    {
        $this->settingManager = $settingsManager;
    }


    /**
     * @param string $url
     * @param int $maxEntities
     * @param int|null $limit
     * @param bool $editable
     * @param string $order
     * @return GridConfigurationBuilder
     * @throws \Trinity\Bundle\SettingsBundle\Exception\PropertyNotExistsException
     */
    public function createGridConfigurationBuilder(
        string $url,
        int $maxEntities = 1,
        int $limit = null,
        bool $editable = false,
        string $order = 'id:ASC'
    ) {
        if (!$limit) {
            $limit = $this->settingManager->get('items_on_page');
        }
        return new GridConfigurationBuilder($url, $maxEntities, $limit, $editable, $order);
    }
}
