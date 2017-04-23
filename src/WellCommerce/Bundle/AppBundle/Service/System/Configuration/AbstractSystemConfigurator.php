<?php

namespace WellCommerce\Bundle\AppBundle\Service\System\Configuration;

use Symfony\Component\HttpKernel\KernelInterface;
use WellCommerce\Bundle\AppBundle\Entity\SystemConfiguration;
use WellCommerce\Bundle\AppBundle\Manager\SystemConfigurationManager;

/**
 * Class AbstractSystemConfigurator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractSystemConfigurator implements SystemConfiguratorInterface
{
    /**
     * @var SystemConfigurationManager
     */
    protected $manager;
    
    /**
     * @var KernelInterface
     */
    protected $kernel;
    
    public function __construct(KernelInterface $kernel, SystemConfigurationManager $manager)
    {
        $this->kernel  = $kernel;
        $this->manager = $manager;
    }
    
    protected function setParamValue(string $paramName, string $paramValue)
    {
        $this->manager->updateParameter($paramName, $paramValue);
    }
    
    protected function getParamValue(string $name): string
    {
        $configuration = $this->manager->getConfiguration($name);
        if ($configuration instanceof SystemConfiguration) {
            return $configuration->getParamValue();
        }
        
        return $this->getDefaults()[$name] ?? '';
    }
}
