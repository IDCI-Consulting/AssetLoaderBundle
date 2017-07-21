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
        $renderedAssets = array();
        if (!$assetProvider instanceof AssetProviderInterface) {
            $assetProvider = $this->assetProviderRegistry->getOneByAlias($assetProvider);
        }

        $renderedAssets[] = $this->assetRenderer->renderAssets($assetProvider);

        $this->appendHtmlToDOM($renderedAssets);
    }

    /**
     * Load the assets for all asset providers in the DOM
     */
    public function loadAll()
    {
        $renderedAssets = array();
        foreach ($this->assetProviderRegistry as $assetProvider) {
            $renderedAssets[] = $this->assetRenderer->renderAssets($assetProvider);
        }

        $this->appendHtmlToDOM($renderedAssets);
    }

    /**
     * Append HTML at the end of the body
     *
     * @param array $html
     */
    private function appendHTMLToDOM(array $renderedAssets)
    {
        $this->dispatcher->addListener(KernelEvents::RESPONSE, function ($event) use ($renderedAssets) {
            $response = $event->getResponse();
            $content = $newContent = $response->getContent();
            $pos = strripos($content, '</body>');

            foreach ($renderedAssets as $renderedAsset) {
                if (!self::isLoaded($renderedAsset, $content)) {
                    $newContent = substr($content, 0, $pos) . $renderedAsset . substr($content, $pos);
                }
            }

            $response->setContent($newContent);
            $event->setResponse($response);
        });
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

