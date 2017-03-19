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

namespace WellCommerce\Bundle\FeatureBundle\Enhancer;

use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\FeatureBundle\Entity\FeatureSet;
use WellCommerce\Bundle\FeatureBundle\Entity\ProductFeature;
use WellCommerce\Component\DoctrineEnhancer\AbstractMappingEnhancer;
use WellCommerce\Component\DoctrineEnhancer\Definition\ManyToOneDefinition;
use WellCommerce\Component\DoctrineEnhancer\Definition\MappingDefinitionCollection;
use WellCommerce\Component\DoctrineEnhancer\Definition\OneToManyDefinition;
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
        $collection->add(new ManyToOneDefinition([
            'fieldName'    => 'featureSet',
            'joinColumns'  => [
                [
                    'name'                 => 'feature_set_id',
                    'referencedColumnName' => 'id',
                    'onDelete'             => 'SET NULL',
                ],
            ],
            'targetEntity' => FeatureSet::class,
        ]));
        
        $collection->add(new OneToManyDefinition([
            'fieldName'     => 'features',
            'targetEntity'  => ProductFeature::class,
            'mappedBy'      => 'product',
            'orphanRemoval' => true,
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
