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

namespace WellCommerce\Bundle\CatalogBundle\Enhancer;

use WellCommerce\Bundle\CatalogBundle\Entity\Extra\ProductExtraTrait;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Component\DoctrineEnhancer\AbstractMappingEnhancer;
use WellCommerce\Component\DoctrineEnhancer\Definition\MappingDefinitionCollection;

/**
 * Class ProductMappingEnhancer
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ProductMappingEnhancer extends AbstractMappingEnhancer
{
    protected function configureMappingDefinition(MappingDefinitionCollection $collection)
    {
    }
    
    public function getSupportedEntityClass(): string
    {
        return Product::class;
    }
    
    public function getSupportedEntityExtraTraitClass(): string
    {
        return ProductExtraTrait::class;
    }
}
