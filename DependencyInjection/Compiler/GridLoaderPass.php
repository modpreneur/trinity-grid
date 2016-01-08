<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Validator\Mapping\Loader\FilesLoader;


/**
 * Class GridLoaderPass
 * @package Trinity\DependencyInjection\Compiler
 */
class GridLoaderPass implements CompilerPassInterface
{
    /**
     * GridLoaderPass constructor.
     */
    public function __construct()
    {

    }


    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('trinity.grid.loader')) {
            return;
        }

        $definition = $container->getDefinition('trinity.grid.loader');

        $enabledDrivers = explode(",", $container->getParameter("trinity.grid"));
        foreach ($container->findTaggedServiceIds('trinity.notification.driver') as $serviceId => $key) {
            if (in_array($serviceId, $enabledDrivers)) {
                $definition->addMethodCall('addGrid', [new Reference($serviceId)]);
            }
        }

    }
}