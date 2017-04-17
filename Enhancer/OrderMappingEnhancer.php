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

namespace WellCommerce\Bundle\InvoiceBundle\Enhancer;

use WellCommerce\Bundle\InvoiceBundle\Entity\Invoice;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Component\DoctrineEnhancer\AbstractMappingEnhancer;
use WellCommerce\Component\DoctrineEnhancer\Definition\MappingDefinitionCollection;
use WellCommerce\Component\DoctrineEnhancer\Definition\OneToManyDefinition;
use WellCommerce\Extra\OrderBundle\Entity\OrderExtraTrait;

/**
 * Class OrderMappingEnhancer
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class OrderMappingEnhancer extends AbstractMappingEnhancer
{
    protected function configureMappingDefinition(MappingDefinitionCollection $collection)
    {
        $collection->add(new OneToManyDefinition([
            'fieldName'     => 'invoices',
            'targetEntity'  => Invoice::class,
            'mappedBy'      => 'order',
            'orphanRemoval' => true,
        ]));
    }
    
    public function getSupportedEntityClass(): string
    {
        return Order::class;
    }
    
    public function getExtraTraitClass(): string
    {
        return OrderExtraTrait::class;
    }
}
