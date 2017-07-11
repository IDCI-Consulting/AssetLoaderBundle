<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle;

use IDCI\Bundle\AssetLoaderBundle\DependencyInjection\Compiler\AssetLoaderCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use IDCI\Bundle\AssetLoaderBundle\DependencyInjection\Compiler\AssetProviderCompilerPass;

class IDCIAssetLoaderBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AssetProviderCompilerPass());
        $container->addCompilerPass(new AssetLoaderCompilerPass());
    }
}
