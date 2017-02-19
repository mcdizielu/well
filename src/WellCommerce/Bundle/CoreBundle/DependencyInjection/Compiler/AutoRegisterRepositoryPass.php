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
use WellCommerce\Bundle\CoreBundle\DependencyInjection\Definition\RepositoryDefinitionFactory;

/**
 * Class AutoRegisterRepositoryPass
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class AutoRegisterRepositoryPass extends AbstractAutoRegisterPass
{
    public function process(ContainerBuilder $container)
    {
        $definitionFactory = new RepositoryDefinitionFactory();
        $classes           = $this->classFinder->findEntityClasses($this->bundle);
        
        foreach ($classes as $baseName => $className) {
            $serviceId  = $this->serviceIdGenerator->getServiceId($baseName, 'repository');
            $definition = $definitionFactory->create($className);
            if (false === $container->has($serviceId)) {
                $container->setDefinition($serviceId, $definition);
            }
        }
    }
}
