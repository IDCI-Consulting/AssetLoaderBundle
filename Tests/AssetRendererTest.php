<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\Tests;

use IDCI\Bundle\AssetLoaderBundle\AssetRenderer\AssetRenderer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AssetRendererTest extends WebTestCase
{
    /**
     * @var AssetRenderer
     */
    private $assetRenderer;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        require_once __DIR__.'/AppKernel.php';

        $kernel = new \AppKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer();
        $this->assetRenderer = $container->get('idci_asset_loader.asset_renderer');
    }

    public function testRenderAssets()
    {
        $assetProvider = new MyAssetProvider();

        $renderedAssets = $this->assetRenderer->renderAssets($assetProvider);

        $this->assertEquals('asset2asset1', $renderedAssets);
    }
}
