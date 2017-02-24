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
use WellCommerce\Bundle\CoreBundle\DependencyInjection\Definition\FormBuilderDefinitionFactory;

/**
 * Class AutoRegisterFormBuilderPass
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class AutoRegisterFormBuilderPass extends AbstractAutoRegisterPass
{
    public function process(ContainerBuilder $container)
    {
        $this->registerAdminFormBuilders($container);
        $this->registerFrontFormBuilders($container);
    }
    
    private function registerAdminFormBuilders(ContainerBuilder $container)
    {
        $definitionFactory = new FormBuilderDefinitionFactory();
        $classes           = $this->classFinder->findFormBuilderClasses($this->bundle, 'Admin');
        
        foreach ($classes as $baseName => $className) {
            $formBuilderServiceId = $this->serviceIdGenerator->getServiceId($baseName, 'form_builder.admin');
            if (false === $container->has($formBuilderServiceId)) {
                $definition = $definitionFactory->create($className);
                $container->setDefinition($formBuilderServiceId, $definition);
            }
        }
    }
    
    private function registerFrontFormBuilders(ContainerBuilder $container)
    {
        $definitionFactory = new FormBuilderDefinitionFactory();
        $classes           = $this->classFinder->findFormBuilderClasses($this->bundle, 'Front');
        
        foreach ($classes as $baseName => $className) {
            $formBuilderServiceId = $this->serviceIdGenerator->getServiceId($baseName, 'form_builder.front');
            if (false === $container->has($formBuilderServiceId)) {
                $definition = $definitionFactory->create($className);
                $container->setDefinition($formBuilderServiceId, $definition);
            }
        }
    }
}
