<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\Tests;

use IDCI\Bundle\AssetLoaderBundle\AssetProvider\AssetProviderInterface;
use IDCI\Bundle\AssetLoaderBundle\Model\AssetCollection;

class FakeAssetProvider implements AssetProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAssetCollection()
    {
        return new AssetCollection();
    }
}
