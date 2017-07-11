<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\AssetProvider;

use IDCI\Bundle\AssetLoaderBundle\Model\Asset;

interface AssetProviderInterface
{
    /**
     * Get the assets
     *
     * @return Asset[]
     */
    public function getAssets();
}
