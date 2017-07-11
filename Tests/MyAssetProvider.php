<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\Tests;

use IDCI\Bundle\AssetLoaderBundle\AssetProvider\AssetProviderInterface;
use IDCI\Bundle\AssetLoaderBundle\Model\Asset;

class MyAssetProvider implements AssetProviderInterface
{
    public function getAssets()
    {
        return array(
            new Asset('@twig_path/asset1.html.twig', array(
                'title' => 'asset1',
                'options' => array('valid' => 'ok')
            )),
            new Asset('@twig_path/asset2.html.twig', array(
                'title' => 'asset2',
                'options' => array('valid' => 'ok')
            )),
            new Asset('@twig_path/asset1.html.twig', array(
                'title' => 'asset1',
                'options' => array('valid' => 'ok')
            )),
        );
    }
}
