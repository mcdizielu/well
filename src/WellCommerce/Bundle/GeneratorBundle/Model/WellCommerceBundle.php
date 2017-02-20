<?php

namespace WellCommerce\Bundle\GeneratorBundle\Model;

use Sensio\Bundle\GeneratorBundle\Model\Bundle;
use WellCommerce\Bundle\CoreBundle\Helper\Helper;

/**
 * Class WellCommerceBundle
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class WellCommerceBundle extends Bundle
{
    /**
     * @var string
     */
    protected $prefix;
    
    public function __construct($prefix, $namespace, $name, $targetDirectory, $configurationFormat, $isShared)
    {
        parent::__construct($namespace, $name, $targetDirectory, $configurationFormat, $isShared);
        $this->prefix = $prefix;
    }
    
    public function getPrefix(): string
    {
        return $this->prefix;
    }
    
    public function getAlias(): string
    {
        return Helper::snake($this->prefix);
    }
    
    public function getRoutingConfigurationFilename()
    {
        return 'routing.yml';
    }
    
    public function getServicesConfigurationFilename()
    {
        return 'config.yml';
    }
}
