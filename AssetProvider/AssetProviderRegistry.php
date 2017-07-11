<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\AssetProvider;

class AssetProviderRegistry
{
    /**
     * @var array
     */
    protected $assetProviders;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->assetProviders = array();
    }

    /**
     * Sets an asset provider identify by an alias.
     *
     * @param string                 $alias         The asset provider alias
     * @param AssetProviderInterface $assetProvider The asset provider
     *
     * @return AssetProviderRegistry
     */
    public function set($alias, AssetProviderInterface $assetProvider)
    {
        $this->assetProviders[$alias] = $assetProvider;

        return $this;
    }

    /**
     * Returns all asset providers
     *
     * @return array
     */
    public function getAll()
    {
        return $this->assetProviders;
    }

    /**
     * Returns an asset provider by alias
     *
     * @param string $alias The asset provider alias
     *
     * @return AssetProviderInterface
     *
     * @throws \InvalidArgumentException if the alias is not a string
     * @throws \InvalidArgumentException if the asset provider does not exist
     */
    public function getOneByAlias($alias)
    {
        if (!is_string($alias)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected argument of type "string", "%s" given',
                is_object($alias) ? get_class($alias) : gettype($alias)
            ));
        }

        if (!isset($this->assetProviders[$alias])) {
            throw new \InvalidArgumentException(sprintf(
                'Could not load asset provider "%s". Available asset providers are %s',
                $alias,
                implode(', ', array_keys($this->assetProviders))
            ));
        }

        return $this->assetProviders[$alias];
    }

    /**
     * Check if the registry has an asset provider for the given alias
     *
     * @param string $alias The alias of the asset provider
     *
     * @return bool
     */
    public function hasByAlias($alias)
    {
        return isset($this->assetProviders[$alias]);
    }
}
