<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\AssetProvider;

use IDCI\Bundle\AssetLoaderBundle\Model\AssetCollection;

interface AssetProviderInterface
{
    /**
     * Get the assets
     *
     * @return AssetCollection
     */
    public function getAssetCollection();
}
