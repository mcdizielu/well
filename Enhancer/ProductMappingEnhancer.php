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

namespace WellCommerce\Bundle\SimilarProductBundle\Enhancer;

use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Component\DoctrineEnhancer\AbstractMappingEnhancer;
use WellCommerce\Component\DoctrineEnhancer\Definition\ManyToManyDefinition;
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
        $joinColumns        = [];
        $inverseJoinColumns = [];

        $joinColumns[] = [
            'name'                 => 'product_id',
            'referencedColumnName' => 'id',
            'nullable'             => false,
            'unique'               => false,
            'onDelete'             => 'CASCADE',
        ];

        $inverseJoinColumns[] = [
            'name'                 => 'similar_product_id',
            'referencedColumnName' => 'id',
            'nullable'             => false,
            'unique'               => false,
            'onDelete'             => 'CASCADE',
        ];

        $collection->add(new ManyToManyDefinition([
            'fieldName'    => 'similarProducts',
            'targetEntity' => Product::class,
            'joinTable'    => [
                'name'               => 'similar_product',
                'joinColumns'        => $joinColumns,
                'inverseJoinColumns' => $inverseJoinColumns,
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
