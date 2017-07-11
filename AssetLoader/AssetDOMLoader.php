<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\AssetLoader;

use IDCI\Bundle\AssetLoaderBundle\AssetProvider\AssetProviderInterface;
use IDCI\Bundle\AssetLoaderBundle\AssetProvider\AssetProviderRegistry;
use IDCI\Bundle\AssetLoaderBundle\AssetRenderer\AssetRenderer;
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
        AssetRenderer $twig,
        AssetProviderRegistry $assetProviderRegistry
    ) {
        $this->twig = $twig;
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

        $renderedAssets = $this->assetRenderer->renderAssets($assetProvider);

        $this->appendHtmlToDOM($renderedAssets);
    }

    /**
     * Load the assets for all asset providers in the DOM
     */
    public function loadAll()
    {
        $renderedAssets = '';
        foreach ($this->assetProviderRegistry as $assetProvider) {
            $renderedAssets .= $this->assetRenderer->renderAssets($assetProvider);
        }

        $this->appendHtmlToDOM($renderedAssets);
    }

    /**
     * Append HTML at the end of the body
     *
     * @param $html
     */
    private function appendHTMLToDOM($html)
    {
        $this->dispatcher->addListener('kernel.response', function ($event) use ($html) {
            $response = $event->getResponse();
            $content = $response->getContent();
            $pos = strripos($content, '</body>');

            $newContent = substr($content, 0, $pos) . $html . substr($content, $pos);

            $response->setContent($newContent);
            $event->setResponse($response);
        });
    }
}
