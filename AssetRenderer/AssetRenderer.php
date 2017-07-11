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
        $assets = $assetProvider->getAssets();
        $renderedAssets = '';

        /*
         * Avoid to load identical assets multiple times.
         * It may be caused by the buildView() method of a form type which
         * will be triggered as many times as there are widgets for this form type in a page
         */
        $assets = array_unique($assets, SORT_REGULAR);

        foreach ($assets as $asset) {
            if (!($asset instanceof Asset)) {
                throw new \Exception(sprintf(
                    'Invalid asset type for assetProvider %s: getAssets must return an array of Asset Objects',
                    get_class($assetProvider)
                ));
            }

            $renderedAssets .= $this->twig->render(
                $asset->getTemplatePath(),
                $asset->getParameters()
            );
        }

        return $renderedAssets;
    }
}
