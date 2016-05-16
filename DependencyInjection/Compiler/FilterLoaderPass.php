<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class FilterLoaderPass
 * @package Trinity\DependencyInjection\Compiler
 */
class FilterLoaderPass implements CompilerPassInterface
{
    /**
     *
     * @param ContainerBuilder $container
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('trinity.grid.manager')) {
            return;
        }

        $definition = $container->getDefinition('trinity.grid.manager');
        $taggedServices = $container->findTaggedServiceIds('trinity.grid.filter');


        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('addFilter', [new Reference($id)]);
            }
        }
    }
}
