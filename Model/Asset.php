<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\AssetLoaderBundle\Model;

class Asset
{
    /**
     * @var string
     */
    protected $templatePath;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * Constructor
     *
     * @param string $templatePath
     * @param array $parameters
     */
    public function __construct($templatePath, array $parameters = array())
    {
        $this->templatePath = $templatePath;
        $this->parameters   = $parameters;
    }

    /**
     * Get the asset template path
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }
    /**
     * Get the asset parameters
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
