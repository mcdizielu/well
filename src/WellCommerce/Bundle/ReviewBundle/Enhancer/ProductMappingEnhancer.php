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

namespace WellCommerce\Bundle\ReviewBundle\Enhancer;

use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Component\DoctrineEnhancer\AbstractMappingEnhancer;
use WellCommerce\Component\DoctrineEnhancer\Definition\FieldDefinition;
use WellCommerce\Component\DoctrineEnhancer\Definition\MappingDefinitionCollection;
use WellCommerce\Extra\CatalogBundle\Entity\ProductExtraTrait;

/**
 * Class ProductMappingEnhancer
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ProductMappingEnhancer extends AbstractMappingEnhancer
{
    protected function configureMappingDefinition(MappingDefinitionCollection $collection)
    {
        $collection->add(new FieldDefinition([
            'fieldName'  => 'enableReviews',
            'type'       => 'boolean',
            'unique'     => false,
            'nullable'   => false,
            'columnName' => 'enable_reviews',
            'options'    => [
                'default' => true,
            ],
        ]));
    }
    
    public function getSupportedEntityClass(): string
    {
        return Product::class;
    }
    
    public function getExtraTraitClass(): string
    {
        return ProductExtraTrait::class;
    }
}
