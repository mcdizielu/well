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
use WellCommerce\Bundle\CoreBundle\DependencyInjection\Definition\AdminControllerDefinitionFactory;

/**
 * Class AutoRegisterControllerPass
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class AutoRegisterControllerPass extends AbstractAutoRegisterPass
{
    public function process(ContainerBuilder $container)
    {
        $definitionFactory = new AdminControllerDefinitionFactory();
        $classes           = $this->classFinder->findAdminControllerClasses($this->bundle);
        
        foreach ($classes as $baseName => $className) {
            $controllerServiceId = $this->serviceIdGenerator->getServiceId($baseName, 'controller.admin');
            
            if (false === $container->has($controllerServiceId)) {
                $manager     = $this->createReference($baseName, 'manager', $container);
                $formBuilder = $this->createReference($baseName, 'form_builder.admin', $container);
                $dataGrid    = $this->createReference($baseName, 'datagrid', $container);
                $dataSet     = $this->createReference($baseName, 'dataset.admin', $container);
                $definition  = $definitionFactory->create($className, $manager, $formBuilder, $dataGrid, $dataSet);
                $container->setDefinition($controllerServiceId, $definition);
            }
        }
    }
}
