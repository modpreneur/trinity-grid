<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


/**
 * Class GridLoaderPass
 * @package Trinity\DependencyInjection\Compiler
 */
class GridLoaderPass implements CompilerPassInterface
{
    /**
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('trinity.grid.manager')) {
            return;
        }

        $definition = $container->getDefinition('trinity.grid.manager');
        $taggedServices = $container->findTaggedServiceIds('trinity.grid');


        foreach ($taggedServices as $id => $tags) {
            foreach($tags as $attributes){
                $definition->addMethodCall('addGrid', [$attributes['alias'], new Reference($id)]);
            }
        }

    }
}