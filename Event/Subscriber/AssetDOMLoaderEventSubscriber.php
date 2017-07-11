<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\Event\Subscriber;

use IDCI\Bundle\AssetLoaderBundle\AssetProvider\AssetProviderRegistry;
use IDCI\Bundle\AssetLoaderBundle\AssetRenderer\AssetRenderer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AssetDOMLoaderEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var AssetRenderer
     */
    private $assetRenderer;

    /**
     * @var AssetProviderRegistry
     */
    private $assetProviderRegistry;

    /**
     * @var bool
     */
    private $autoLoad;

    /**
     * Constructor
     *
     * @param AssetRenderer            $assetRenderer
     * @param AssetProviderRegistry    $assetProviderRegistry
     * @param boolean                  $autoLoad
     */
    public function __construct(
        AssetRenderer $assetRenderer,
        AssetProviderRegistry $assetProviderRegistry,
        $autoLoad
    ) {
        $this->assetRenderer         = $assetRenderer;
        $this->assetProviderRegistry = $assetProviderRegistry;
        $this->autoLoad              = $autoLoad;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::RESPONSE => array(
                array('appendAssets', 0),
            )
        );
    }

    /**
     * Append the assets for all asset providers in the DOM
     *
     * @param FilterResponseEvent $event
     * @throws \Exception
     */
    public function appendAssets(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if (!$this->autoLoad) {
            return;
        }

        $response = $event->getResponse();
        $content = $response->getContent();
        $pos = strripos($content, '</body>');
        $renderedAssets = '';

        foreach ($this->assetProviderRegistry->getAll() as $assetProvider) {
            $renderedAssets .= $this->assetRenderer->renderAssets($assetProvider);
        }

        $newContent = substr($content, 0, $pos) . $renderedAssets . substr($content, $pos);

        $response->setContent($newContent);
        $event->setResponse($response);
    }
}
