<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\AssetLoader;

use IDCI\Bundle\AssetLoaderBundle\AssetProvider\AssetProviderRegistry;
use IDCI\Bundle\AssetLoaderBundle\AssetRenderer\AssetRenderer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class AssetDOMLoaderEventListener
{
    /**
     * AssetRenderer
     */
    private $assetRenderer;

    /**
     * EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var AssetProviderRegistry
     */
    private $assetProviderRegistry;

    /**
     * Constructor
     *
     * @param EventDispatcherInterface $dispatcher
     * @param AssetRenderer            $twig
     * @param AssetProviderRegistry    $assetProviderRegistry
     * @param boolean                  $autoLoad
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        AssetRenderer $twig,
        AssetProviderRegistry $assetProviderRegistry,
        $autoLoad
    ) {
        $this->twig                  = $twig;
        $this->dispatcher            = $dispatcher;
        $this->assetProviderRegistry = $assetProviderRegistry;
        $this->autoLoad              = $autoLoad;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$this->autoLoad) {
            return;
        }

        $response = $event->getResponse();
        $content = $response->getContent();
        $pos = strripos($content, '</body>');
        $renderedAssets = '';

        foreach ($this->assetProviderRegistry as $assetProvider) {
            $renderedAssets .= $this->assetRenderer->renderAssets($assetProvider);
        }

        $newContent = substr($content, 0, $pos) . $renderedAssets . substr($content, $pos);

        $response->setContent($newContent);
        $event->setResponse($response);
    }
}
