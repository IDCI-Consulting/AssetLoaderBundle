<?php

/**
 * @author:  Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\Tests;

use IDCI\Bundle\AssetLoaderBundle\AssetProvider\AssetProviderRegistry;
use IDCI\Bundle\AssetLoaderBundle\AssetRenderer\AssetRenderer;
use IDCI\Bundle\AssetLoaderBundle\AssetLoader\AssetDOMLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;

class AssetDOMLoaderTest extends TestCase
{
    private $dispatcher;

    private $kernel;

    private $assetDOMLoader;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $twigPath = __DIR__.'/templates';
        $assetProvider = new MyAssetProvider();
        $fileSystemLoader = new \Twig_Loader_Filesystem();
        $fileSystemLoader->addPath(__DIR__.'/templates', 'twig_path');
        $assetRenderer = new AssetRenderer(new \Twig_Environment($fileSystemLoader, array(
            'cache' => 'cache',
        )));
        $assetProviderRegistry = new AssetProviderRegistry();
        $assetProviderRegistry->set('my_provider', $assetProvider);

        $this->dispatcher = new EventDispatcher();
        $this->kernel = $this->getMockBuilder('Symfony\Component\HttpKernel\HttpKernelInterface')->getMock();
        $this->assetDOMLoader = new AssetDOMLoader($this->dispatcher, $assetRenderer, $assetProviderRegistry);
    }

    public function testLoad()
    {
        $html =<<<EOF
<html>
    <head></head>
    <body></body>
</html>
EOF;

        $expectedHtml =<<<EOF
<html>
    <head></head>
    <body>asset2asset1</body>
</html>
EOF;
        $response = new Response($html);
        $event = new FilterResponseEvent($this->kernel, new Request(), HttpKernelInterface::MASTER_REQUEST, $response);

        $this->assetDOMLoader->load('my_provider');
        $this->dispatcher->dispatch(KernelEvents::RESPONSE, $event);
        $this->assertEquals($expectedHtml, $event->getResponse()->getContent());
    }
}
