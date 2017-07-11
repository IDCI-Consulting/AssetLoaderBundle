<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\Tests;

use IDCI\Bundle\AssetLoaderBundle\AssetProvider\AssetProviderRegistry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AssetProviderRegistryTest extends WebTestCase
{
    /**
     * @var AssetProviderRegistry
     */
    private $assetProviderRegistry;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        require_once __DIR__.'/AppKernel.php';

        $kernel = new \AppKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer();
        $this->assetProviderRegistry = $container->get('idci_asset_loader.asset_provider.registry');
    }

    public function testHasByAlias()
    {
        $this->assertTrue($this->assetProviderRegistry->hasByAlias('fake_asset_provider'));
    }
}
