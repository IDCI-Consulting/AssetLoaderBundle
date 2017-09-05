<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\Tests\Fixtures\AssetProvider;

use IDCI\Bundle\AssetLoaderBundle\AssetProvider\AssetProviderInterface;
use IDCI\Bundle\AssetLoaderBundle\Model\Asset;
use IDCI\Bundle\AssetLoaderBundle\Model\AssetCollection;

class MyAssetProvider implements AssetProviderInterface
{
    public function getAssetCollection()
    {
        $collection = new AssetCollection();

        $collection->add(new Asset('@twig_path/asset1.html.twig', array(
            'title' => 'asset1',
            'options' => array('valid' => 'ok')
        ), 0));

        $collection->add(new Asset('@twig_path/asset1.html.twig', array(
            'title' => 'asset2',
            'options' => array('valid' => 'ok')
        ), 1));

        $collection->add(new Asset('@twig_path/asset1.html.twig', array(
            'title' => 'asset1',
            'options' => array('valid' => 'ok')
        ), 0));

        return $collection;
    }
}
