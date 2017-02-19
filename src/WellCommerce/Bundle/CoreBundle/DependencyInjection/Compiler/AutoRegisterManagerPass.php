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
use Symfony\Component\DependencyInjection\Reference;
use WellCommerce\Bundle\CoreBundle\DependencyInjection\Definition\ManagerDefinitionFactory;

/**
 * Class AutoRegisterManagerPass
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class AutoRegisterManagerPass extends AbstractAutoRegisterPass
{
    public function process(ContainerBuilder $container)
    {
        $definitionFactory = new ManagerDefinitionFactory();
        $classes           = $this->classFinder->findManagerClasses($this->bundle);
        foreach ($classes as $baseName => $className) {
            $managerServiceId = $this->serviceIdGenerator->getServiceId($baseName, 'manager');
            if (false === $container->has($managerServiceId)) {
                $factoryServiceId    = $this->serviceIdGenerator->getServiceId($baseName, 'factory');
                $repositoryServiceId = $this->serviceIdGenerator->getServiceId($baseName, 'repository');
                $factory             = $container->has($factoryServiceId) ? new Reference($factoryServiceId) : null;
                $repository          = $container->has($repositoryServiceId) ? new Reference($repositoryServiceId) : null;
                $definition          = $definitionFactory->create($className, $factory, $repository);
                $container->setDefinition($managerServiceId, $definition);
            }
        }
    }
}
