<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\Event\Subscriber;

use IDCI\Bundle\AssetLoaderBundle\AssetLoader\AssetDOMLoader;
use IDCI\Bundle\AssetLoaderBundle\AssetProvider\AssetProviderRegistry;
use IDCI\Bundle\AssetLoaderBundle\AssetRenderer\AssetRenderer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
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
    private $loadAll;

    /**
     * @var array
     */
    private $loadOnly;

    /**
     * Constructor.
     *
     * @param AssetRenderer         $assetRenderer
     * @param AssetProviderRegistry $assetProviderRegistry
     * @param bool                  $loadAll
     * @param array                 $loadOnly
     */
    public function __construct(
        AssetRenderer $assetRenderer,
        AssetProviderRegistry $assetProviderRegistry,
        $loadAll,
        $loadOnly
    ) {
        $this->assetRenderer = $assetRenderer;
        $this->assetProviderRegistry = $assetProviderRegistry;
        $this->loadAll = $loadAll;
        $this->loadOnly = $loadOnly;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::RESPONSE => array(
                array('appendAssets', 0),
            ),
        );
    }

    /**
     * Append the assets for all asset providers in the DOM.
     *
     * @param FilterResponseEvent $event
     *
     * @throws \Exception
     */
    public function appendAssets(FilterResponseEvent $event)
    {
        if (Response::class !== get_class($event->getResponse())) {
            return;
        }

        if (!$event->isMasterRequest()) {
            return;
        }

        if (!$this->loadAll && empty($this->loadOnly)) {
            return;
        }

        if ($this->loadAll) {
            $assetProviders = $this->assetProviderRegistry->getAll();
        } else {
            $assetProviders = $this->filterProviders();
        }

        $renderedAssets = array();
        foreach ($assetProviders as $assetProvider) {
            $renderedAssets = array_unique(array_merge(
                $renderedAssets,
                $this->assetRenderer->getRenderedAssets($assetProvider)
            ));
        }

        $response = $event->getResponse();
        $content = AssetDOMLoader::append($renderedAssets, $response->getContent());

        $response->setContent($content);
        $event->setResponse($response);
    }

    /**
     * Filter the providers with the loadOnly parameter.
     *
     * @return array
     */
    private function filterProviders()
    {
        $providers = array();
        foreach ($this->loadOnly as $providerAlias) {
            $providers[] = $this->assetProviderRegistry->getOneByAlias($providerAlias);
        }

        return $providers;
    }
}
