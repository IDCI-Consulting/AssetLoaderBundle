<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\Tests;

use IDCI\Bundle\AssetLoaderBundle\AssetRenderer\AssetRenderer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use IDCI\Bundle\AssetLoaderBundle\Tests\Fixtures\AssetProvider\MyAssetProvider;

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
        $this->assetRenderer = $container->get(AssetRenderer::class);
    }

    public function testGetRenderedAssets()
    {
        $assetProvider = new MyAssetProvider();

        $renderedAssets = $this->assetRenderer->getRenderedAssets($assetProvider);

        $this->assertEquals(array('asset2', 'asset1'), $renderedAssets);
    }
}
