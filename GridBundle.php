<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Trinity\Bundle\GridBundle\DependencyInjection\Compiler\FilterLoaderPass;
use Trinity\Bundle\GridBundle\DependencyInjection\Compiler\GridLoaderPass;


/**
 * Class GridBundle
 * @package Trinity\Bundle\GridBundle
 */
class GridBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new GridLoaderPass());
        $container->addCompilerPass(new FilterLoaderPass());

    }

}