<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 * 
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 * 
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\CoreBundle\DependencyInjection\Definition;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class DataSetDefinitionFactory
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class DataSetDefinitionFactory
{
    public function create(string $repositoryServiceId, string $class): Definition
    {
        $definition = new Definition($class);
        $definition->setArguments([new Reference($repositoryServiceId), new Reference('dataset.manager')]);
        $definition->setConfigurator([new Reference('dataset.configurator'), 'configure']);
        $definition->addMethodCall('setContainer', [new Reference('service_container')]);
        $definition->setLazy(true);
        
        return $definition;
    }
}
