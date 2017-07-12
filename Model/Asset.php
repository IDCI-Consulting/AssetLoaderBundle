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
     * @var int
     */
    protected $priority;

    /**
     * Constructor
     *
     * @param string $templatePath
     * @param array $parameters
     * @param int $priority
     */
    public function __construct($templatePath, array $parameters = array(), $priority = -1)
    {
        $this->templatePath = $templatePath;
        $this->parameters   = $parameters;
        $this->priority     = $priority;
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

    /**
     * Get the asset priority
     */
    public function getPriority()
    {
        return $this->priority;
    }
}
