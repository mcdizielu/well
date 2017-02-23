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
use WellCommerce\Bundle\CoreBundle\DependencyInjection\Definition\DataGridDefinitionFactory;

/**
 * Class AutoRegisterDataGridPass
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class AutoRegisterDataGridPass extends AbstractAutoRegisterPass
{
    public function process(ContainerBuilder $container)
    {
        $definitionFactory = new DataGridDefinitionFactory();
        $classes           = $this->classFinder->findDataGridClasses($this->bundle);
        foreach ($classes as $baseName => $className) {
            $dataGridServiceId = $this->serviceIdGenerator->getServiceId($baseName, 'datagrid');
            if (false === $container->has($dataGridServiceId)) {
                $definition = $definitionFactory->create($className);
                $container->setDefinition($dataGridServiceId, $definition);
            }
        }
    }
}
