<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\AssetRenderer;

use IDCI\Bundle\AssetLoaderBundle\AssetProvider\AssetProviderInterface;
use IDCI\Bundle\AssetLoaderBundle\Model\Asset;
use \Twig_Environment as Twig;

class AssetRenderer
{
    /**
     * Twig
     */
    private $twig;

    /**
     * Constructor
     *
     * @param Twig $twig
     */
    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function renderAssets(AssetProviderInterface $assetProvider)
    {
        $renderedAssets = '';
        foreach ($assetProvider->getAssetCollection()->getAll() as $asset) {
            $renderedAssets .= $this->twig->render(
                $asset->getTemplatePath(),
                $asset->getParameters()
            );
        }

        return $renderedAssets;
    }
}
