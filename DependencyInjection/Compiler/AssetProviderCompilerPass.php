<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class AssetProviderCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('IDCI\Bundle\AssetLoaderBundle\AssetProvider\AssetProviderRegistry')) {
            return;
        }

        $registryDefinition = $container->getDefinition('IDCI\Bundle\AssetLoaderBundle\AssetProvider\AssetProviderRegistry');

        foreach ($container->findTaggedServiceIds('idci_asset_loader.asset_provider') as $id => $tags) {
            foreach ($tags as $attributes) {
                $alias = isset($attributes['alias'])
                    ? $attributes['alias']
                    : $id
                ;

                $registryDefinition->addMethodCall(
                    'set',
                    array($alias, new Reference($id))
                );
            }
        }
    }
}
