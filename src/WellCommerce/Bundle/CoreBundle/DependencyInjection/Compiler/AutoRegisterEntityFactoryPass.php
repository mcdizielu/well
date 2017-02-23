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
use WellCommerce\Bundle\CoreBundle\DependencyInjection\Definition\EntityFactoryDefinitionFactory;

/**
 * Class AutoRegisterEntityFactoryPass
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class AutoRegisterEntityFactoryPass extends AbstractAutoRegisterPass
{
    public function process(ContainerBuilder $container)
    {
        $definitionFactory = new EntityFactoryDefinitionFactory();
        $classes           = $this->classFinder->findEntityClasses($this->bundle);
        foreach ($classes as $baseName => $className) {
            $entityFactoryServiceId = $this->serviceIdGenerator->getServiceId($baseName, 'factory');
            if (false === $container->has($entityFactoryServiceId)) {
                $definition = $definitionFactory->create($className);
                $container->setDefinition($entityFactoryServiceId, $definition);
            }
        }
    }
}
