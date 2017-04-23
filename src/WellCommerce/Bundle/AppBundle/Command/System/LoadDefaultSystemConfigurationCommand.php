<?php

namespace WellCommerce\Bundle\AppBundle\Command\System;

use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WellCommerce\Bundle\AppBundle\Manager\SystemConfigurationManager;
use WellCommerce\Bundle\AppBundle\Service\System\Configuration\SystemConfiguratorInterface;

/**
 * Class LoadDefaultSystemConfigurationCommand
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LoadDefaultSystemConfigurationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setDescription('Loads default system configuration');
        $this->setName('wellcommerce:system:load-defaults');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getConfigurators()->map(function (SystemConfiguratorInterface $configurator) {
            $this->loadDefaults($configurator);
        });
    }
    
    private function loadDefaults(SystemConfiguratorInterface $configurator)
    {
        foreach ($configurator->getDefaults() as $paramName => $paramValue) {
            $this->getManager()->updateParameter($paramName, $paramValue);
        }
    }
    
    private function getManager(): SystemConfigurationManager
    {
        return $this->getContainer()->get('system_configuration.manager');
    }
    
    private function getConfigurators(): Collection
    {
        return $this->getContainer()->get('system.configurator.collection');
    }
}
