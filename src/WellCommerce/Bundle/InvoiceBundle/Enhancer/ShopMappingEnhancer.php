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

use WellCommerce\Bundle\AppBundle\Entity\Shop;
use WellCommerce\Component\DoctrineEnhancer\AbstractMappingEnhancer;
use WellCommerce\Component\DoctrineEnhancer\Definition\FieldDefinition;
use WellCommerce\Component\DoctrineEnhancer\Definition\MappingDefinitionCollection;
use WellCommerce\Extra\AppBundle\Entity\ShopExtraTrait;

/**
 * Class ShopMappingEnhancer
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ShopMappingEnhancer extends AbstractMappingEnhancer
{
    protected function configureMappingDefinition(MappingDefinitionCollection $collection)
    {
        $collection->add(new FieldDefinition([
            'fieldName'  => 'invoiceMaturity',
            'type'       => 'integer',
            'unique'     => false,
            'nullable'   => false,
            'columnName' => 'invoice_maturity',
            'options'    => [
                'default' => 7,
            ],
        ]));
        
        $collection->add(new FieldDefinition([
            'fieldName'  => 'invoiceProcessor',
            'type'       => 'string',
            'nullable'   => false,
            'columnName' => 'invoice_processor',
            'options'    => [
                'default' => 'invoice.processor.generic',
            ],
        ]));
    }
    
    public function getSupportedEntityClass(): string
    {
        return Shop::class;
    }
    
    public function getExtraTraitClass(): string
    {
        return ShopExtraTrait::class;
    }
}
