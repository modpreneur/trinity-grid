<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Trinity\Bundle\GridBundle\DependencyInjection\Compiler\FilterLoaderPass;
use Trinity\Bundle\GridBundle\DependencyInjection\Compiler\GridLoaderPass;
use Trinity\Bundle\SettingsBundle\DependencyInjection\TrinitySettingsExtension;

/**
 * Class GridBundle
 * @package Trinity\Bundle\GridBundle
 */
class GridBundle extends Bundle
{

    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new GridLoaderPass());
        $container->addCompilerPass(new FilterLoaderPass());


        $ex = new TrinitySettingsExtension();
        $container->registerExtension( $ex );
    }
}
