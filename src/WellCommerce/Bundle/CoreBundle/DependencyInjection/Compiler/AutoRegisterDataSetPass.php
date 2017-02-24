<?php
/**
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use WellCommerce\Bundle\CoreBundle\DependencyInjection\Definition\DataSetDefinitionFactory;

/**
 * Class AutoRegisterDataSetPass
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class AutoRegisterDataSetPass extends AbstractAutoRegisterPass
{
    public function process(ContainerBuilder $container)
    {
        $this->registerAdminDataSets($container);
        $this->registerFrontDataSets($container);
    }
    
    private function registerAdminDataSets(ContainerBuilder $container)
    {
        $definitionFactory = new DataSetDefinitionFactory();
        $classes           = $this->classFinder->findDataSetClasses($this->bundle, 'Admin');
        
        foreach ($classes as $baseName => $className) {
            $repositoryServiceId = $this->serviceIdGenerator->getServiceId($baseName, 'repository');
            if ($container->has($repositoryServiceId)) {
                $dataSetServiceId = $this->serviceIdGenerator->getServiceId($baseName, 'dataset.admin');
                if (false === $container->has($dataSetServiceId)) {
                    $definition = $definitionFactory->create($repositoryServiceId, $className);
                    $container->setDefinition($dataSetServiceId, $definition);
                }
            }
        }
    }
    
    private function registerFrontDataSets(ContainerBuilder $container)
    {
        $definitionFactory = new DataSetDefinitionFactory();
        $classes           = $this->classFinder->findDataSetClasses($this->bundle, 'Front');
        
        foreach ($classes as $baseName => $className) {
            $repositoryServiceId = $this->serviceIdGenerator->getServiceId($baseName, 'repository');
            if ($container->has($repositoryServiceId)) {
                $dataSetServiceId = $this->serviceIdGenerator->getServiceId($baseName, 'dataset.front');
                if (false === $container->has($dataSetServiceId)) {
                    $definition = $definitionFactory->create($repositoryServiceId, $className);
                    $container->setDefinition($dataSetServiceId, $definition);
                }
            }
        }
    }
}
