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

    private $assetProviderRegistry;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $twigPath = __DIR__.'/templates';
        $fileSystemLoader = new \Twig_Loader_Filesystem();
        $fileSystemLoader->addPath(__DIR__.'/templates', 'twig_path');
        $assetRenderer = new AssetRenderer(new \Twig_Environment($fileSystemLoader, array(
            'cache' => 'cache',
        )));
        $this->dispatcher = new EventDispatcher();
        $this->kernel = $this->getMockBuilder('Symfony\Component\HttpKernel\HttpKernelInterface')->getMock();

        $this->assetProviderRegistry = new AssetProviderRegistry();
        $this->assetProviderRegistry->set('my_provider', new MyAssetProvider());
        $this->assetProviderRegistry->set('my_another_provider', new MyAnotherAssetProvider());

        $this->html =<<<EOF
<html>
    <head></head>
    <body></body>
</html>
EOF;

        $this->assetDOMLoader = new AssetDOMLoader($this->dispatcher, $assetRenderer, $this->assetProviderRegistry);
    }

    public function testLoad()
    {
        $expectedHtml =<<<EOF
<html>
    <head></head>
    <body>asset2asset1</body>
</html>
EOF;
        $event = $this->createEvent();
        $this->assetDOMLoader->load('my_provider');
        $this->dispatcher->dispatch(KernelEvents::RESPONSE, $event);
        $this->assertEquals($expectedHtml, $event->getResponse()->getContent());
    }


    public function testLoadAll()
    {
        $expectedHtml =<<<EOF
<html>
    <head></head>
    <body>asset2asset1asset3</body>
</html>
EOF;

        $event = $this->createEvent();

        $this->assetDOMLoader->loadAll();
        $this->dispatcher->dispatch(KernelEvents::RESPONSE, $event);
        $this->assertEquals($expectedHtml, $event->getResponse()->getContent());
    }

    private function createEvent()
    {
        return new FilterResponseEvent(
            $this->kernel,
            new Request(),
            HttpKernelInterface::MASTER_REQUEST,
            new Response($this->html)
        );
    }
}
