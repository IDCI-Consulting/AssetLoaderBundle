<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\AssetLoader;

use IDCI\Bundle\AssetLoaderBundle\AssetProvider\AssetProviderInterface;
use IDCI\Bundle\AssetLoaderBundle\AssetProvider\AssetProviderRegistry;
use IDCI\Bundle\AssetLoaderBundle\AssetRenderer\AssetRenderer;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AssetDOMLoader
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
     * @param AssetRenderer $twig
     * @param AssetProviderRegistry $assetProviderRegistry
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        AssetRenderer $assetRenderer,
        AssetProviderRegistry $assetProviderRegistry
    ) {
        $this->assetRenderer = $assetRenderer;
        $this->dispatcher = $dispatcher;
        $this->assetProviderRegistry = $assetProviderRegistry;
    }

    /**
     * Load the assets for an asset provider in the DOM
     *
     * @param AssetProviderInterface|string $assetProvider - the assetProvider or an alias
     */
    public function load($assetProvider)
    {
        if (!$assetProvider instanceof AssetProviderInterface) {
            $assetProvider = $this->assetProviderRegistry->getOneByAlias($assetProvider);
        }

        $this->addLoaderListener($this->assetRenderer->getRenderedAssets($assetProvider));
    }

    /**
     * Load the assets for all asset providers in the DOM
     */
    public function loadAll()
    {
        $renderedAssets = array();
        foreach ($this->assetProviderRegistry->getAll() as $assetProvider) {
            $renderedAssets = array_unique(array_merge($renderedAssets, $this->assetRenderer->getRenderedAssets($assetProvider)));
        }

        $this->addLoaderListener($renderedAssets);
    }

    /**
     * Append HTML at the end of the body
     *
     * @param array $html
     */
    private function addLoaderListener(array $renderedAssets)
    {
        $this->dispatcher->addListener(KernelEvents::RESPONSE, function ($event) use ($renderedAssets) {
            $response = $event->getResponse();
            $content = $response->getContent();

            $content = self::append($renderedAssets, $content);

            $response->setContent($content);
            $event->setResponse($response);
        });
    }

    /**
     * Append assets to content
     *
     * @param array $renderedAssets
     * @param string $content
     *
     * @return string
     */
    public static function append(array $renderedAssets, $content)
    {
        $pos = strripos($content, '</body>');

        $assets = '';
        foreach ($renderedAssets as $renderedAsset) {
            if (!self::isLoaded($renderedAsset, $content)) {
                $assets .= $renderedAsset;
            }
        }

        return substr($content, 0, $pos) . $assets . substr($content, $pos);
    }

    /**
     * Check if an asset is already in DOM.
     *
     * @param string $assetContent
     * @param string $html
     *
     * @return boolean
     */
    public static function isLoaded($assetContent, $html)
    {
        return (false !== strpos($html, $assetContent)) ? true : false;
    }
}
