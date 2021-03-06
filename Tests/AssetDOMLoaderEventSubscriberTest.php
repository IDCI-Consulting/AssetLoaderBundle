<?php

/**
 * @author:  Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\Tests;

use IDCI\Bundle\AssetLoaderBundle\AssetProvider\AssetProviderRegistry;
use IDCI\Bundle\AssetLoaderBundle\AssetRenderer\AssetRenderer;
use IDCI\Bundle\AssetLoaderBundle\Event\Subscriber\AssetDOMLoaderEventSubscriber;
use IDCI\Bundle\AssetLoaderBundle\Tests\Fixtures\AssetProvider\MyAssetProvider;
use IDCI\Bundle\AssetLoaderBundle\Tests\Fixtures\AssetProvider\MyAnotherAssetProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;

class AssetDOMLoaderEventSubscriberTest extends TestCase
{
    private $assetProviderRegistry;

    private $assetRender;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $twigPath = __DIR__.'/templates';
        $fileSystemLoader = new \Twig_Loader_Filesystem();
        $fileSystemLoader->addPath(__DIR__.'/templates', 'twig_path');
        $this->assetRenderer = new AssetRenderer(new \Twig_Environment($fileSystemLoader, array(
            'cache' => 'cache',
        )));

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
    }

    public function testAppendAssets()
    {
        $this->assetDOMLoaderEventSubscriber = new AssetDOMLoaderEventSubscriber(
            $this->assetRenderer,
            $this->assetProviderRegistry,
            true,
            array()
        );

        $expectedHtml =<<<EOF
<html>
    <head></head>
    <body>asset2asset1asset3</body>
</html>
EOF;
        $event = $this->createEvent();
        $this->assetDOMLoaderEventSubscriber->appendAssets($event);
        $this->assertEquals($expectedHtml, $event->getResponse()->getContent());
    }

    public function testAppendOnlySomeAssets()
    {
        $this->assetDOMLoaderEventSubscriber = new AssetDOMLoaderEventSubscriber(
            $this->assetRenderer,
            $this->assetProviderRegistry,
            false,
            array('my_another_provider')
        );

        $expectedHtml =<<<EOF
<html>
    <head></head>
    <body>asset3asset1</body>
</html>
EOF;
        $event = $this->createEvent();
        $this->assetDOMLoaderEventSubscriber->appendAssets($event);
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
