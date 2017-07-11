<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\Model;

class AssetCollection
{
    /**
     * @var array
     */
    protected $collection;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->collection = array();
    }

    /**
     * Add an asset to the collection
     *
     * @param Asset $asset
     */
    public function add(Asset $asset)
    {
        /*
         * Avoid to load identical assets multiple times.
         * It may be caused by the buildView() method of a form type which
         * will be triggered as many times as there are widgets for this form type in a page
         */
        if (!in_array($asset, $this->collection)) {
            $this->collection[] = $asset;
        }
    }

    /**
     * Get all assets from the collection
     */
    public function getAll()
    {
        return $this->collection;
    }
}
